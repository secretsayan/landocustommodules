<?php

namespace Drupal\se_pay\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\se_pay\PaymentService;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Controller for CreditCard payment.
 */
class CreditCardController extends ControllerBase {

  /**
   * Payment Service.
   *
   * @var \Drupal\se_pay\PaymentService
   */
  protected $paymentService;

  /**
   * Config factory data member.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructor for this class.
   */
  public function __construct(PaymentService $paymentService, ConfigFactoryInterface $configFactory) {
    $this->paymentService = $paymentService;
    $this->configFactory = $configFactory;
  }

  /**
   * Factory method for dependency injection.
   *
   * @inheritDoc
   */
  public static function create(ContainerInterface $container) {
    return new static(
          $container->get('se_pay.payment'),
          $container->get('config.factory')
      );
  }

  /**
   * Payment handler function.
   */
  public function creditCardPaymentHandler(Request $request) {
    $params = [];
    $data = [];

    $content = $request->getContent();

    if (!empty($content)) {
      $data = json_decode($content, TRUE);
    }

    // $stt = $this->paymentService->getStt();
    $params["stt"] = $data["token"];

    $params['CustRef'] = $data['reference_number'];
    //$params['CustNumber'] = "bambora test";
    $params['Amount'] = intval(floatval($data['payment_amount']) * 100);
    $params['CustNumber'] = $data['customer_name'];

    $params['AccountNumber'] = "onivr36492";
    $params['UserName'] = "wipro.demo.api";
    $params['Password'] = "jtu65uguhT";

    $response = $this->paymentService->doCreditCardPayment($params);

    return new JsonResponse(json_encode($response));
  }

}
