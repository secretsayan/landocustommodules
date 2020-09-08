<?php

namespace Drupal\se_pay\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\se_pay\PaymentService;
use Drupal\Component\Utility\Html;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Controller for Paypal.
 */
class PaypalController extends ControllerBase {

  /**
   * Paypal Service member variable.
   *
   * @var \Drupal\se_pay\PaymentService
   */
  protected $payPalService;

  /**
   * Config factory data member.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructor for this class.
   */
  public function __construct(PaymentService $payPalService, ConfigFactoryInterface $configFactory) {
    $this->payPalService = $payPalService;
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
  public function paypalPaymentHandler(Request $request) {
    $params = [];
    $data = [];
    $config = $this->configFactory->get('se_pay.settings');

    $content = $request->getContent();

    if (!empty($content)) {
      $data = json_decode($content, TRUE);
    }

    $params['CustNumber'] = isset($data['pCustomerNumber']) ? $data['pCustomerNumber'] : "";
    $params['CustomerName'] = isset($data['pCustomerName']) ? $data['pCustomerName'] : "";
    $params['CustRef'] = isset($data['pReferenceNumber']) ? $data['pReferenceNumber'] : "";
    $params['EmailAddress'] = isset($data['pEmailAddress']) ? $data['pEmailAddress'] : "";
    $params['Amount'] = isset($data['pAmount']) ? intval(floatval($data['pAmount']) * 100) : "";

    $amt_ppref = number_format(floatval($data['pAmount']), "2", ".", "");

    // This is hard coded , need to move it to config manager.
    $paypalNumber = "V3R6DPJSAHB4N";

    $params['PP_Reference1'] = base64_encode("||$amt_ppref|||Physical");
    $params['PP_Reference2'] = base64_encode("$paypalNumber|Style1|Simply Energy|1|0|Billing|" . $params['EmailAddress'] . "|0");
    $params['PP_Reference4'] = base64_encode("$amt_ppref||||||Mark");

    // $config->get('sessionid');
    $params['SessionID'] = uniqid();
    $params['UserName'] = $config->get('username');
    $params['password'] = $config->get('password');
    $params['DL'] = $config->get('dl');

    $params['SessionKey'] = time();
    $params["ServerURL"] = base64_encode($config->get('serverurl'));
    $params["UserURL"] = base64_encode($config->get('userurl'));

    // This is hard coded. Need to move to config manager.
    $params["AccountNumber"] = "onivr36942";

    $token_text = $this->payPalService->getIppToken($params);

    $dom = Html::load($token_text);
    $inputs = $dom->getElementsByTagName('input');

    foreach ($inputs as $input) {
      if ($input->getAttribute('name') == 'SST') {
        $output_rc = $input->getAttribute("value");
      }
    }

    \Drupal::logger("paypal")->info("SST Recived" . $output_rc);

    // \Drupal::logger('Paypal')->info("SST Received " . $output_rc);
    // Sessid
    // $params['SessionID']= '5f4a24701be57';.
    $_SESSION["ipp-sst"] = $output_rc;
    $_SESSION["ipp-session"] = $params['SessionID'];

    $iFrameUrl = $config->get('hostippurl')
    . "?&SST=" . $output_rc
    . "&SessionID="
    . $params['SessionID'];
    // \Drupal::logger('Paypal')->info("Request 2: " . $iFrameUrl);
    return new JsonResponse(json_encode($iFrameUrl));
  }

  /**
   * This function handles the incoming notification to the serverURL from IPP.
   */
  public function paypalResponseHandler(Request $request) {

    $incoming = $request->request->all();

    foreach ($incoming as $key => $value) {
      if (!empty($incoming[$key])) {
        $_SESSION["PAYPAL-" . $key] = $value;
      }
    }

    return new JsonResponse(json_encode($incoming));
  }

}
