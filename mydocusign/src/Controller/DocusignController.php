<?php

namespace Drupal\mydocusign\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller Class for Docusign Controller.
 */
class DocusignController extends ControllerBase {

  /**
   * The logger service.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $loggerFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('logger.factory')
    );
  }

  /**
   * Constructs an instance of DocusignController.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   */
  public function __construct(LoggerChannelFactoryInterface $logger_factory) {
    $this->loggerFactory = $logger_factory;
  }

  /**
   * Handler to receive webhook notification from Docusign.
   *
   * @param Symfony\Component\HttpFoundation\Request $request
   *   The incoming request parameter.
   */
  public function statusChangeHandler(Request $request) {
    try {

      $incoming = $request->getContent();

      $xml = simplexml_load_string ($incoming, "SimpleXMLElement", LIBXML_PARSEHUGE);
      $envelope_id = (string)$xml->EnvelopeStatus->EnvelopeID;
      $time_generated = (string)$xml->EnvelopeStatus->TimeGenerated;
      $status = (string)$xml->EnvelopeStatus->Status;

      $this->loggerFactory->get("Docusign all")->info("Envelope ID:$envelope_id");
      $this->loggerFactory->get("Docusign all")->info("Time generated:$time_generated");
      $this->loggerFactory->get("Docusign all")->info("Status:$status");
      $this->loggerFactory->get("Docusign all")->info(print_r($xml,TRUE));

      // TODO
      // Insert in to Maestro and Webform DB


      //$xmlobj = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $incoming);
      //$array_data = strip_tags($xmlobj);

      //$errorResJson = json_encode($incoming);
      //$errorRes = json_decode($errorResJson, TRUE);

      //$xml = simplexml_load_string($incoming);
      //$this->loggerFactory->get("Docusign all")->info(print_r($request->request->all(), TRUE));
      //$this->loggerFactory->get("Docusign")->info(print_r($request, TRUE));
      //$this->loggerFactory->get("Docusign Response XML count")->info(print_r($xml->count(), TRUE));
      //$this->loggerFactory->get("Docusign Response XML count")->info(print_r($xml->asXML(), TRUE));

      return new JsonResponse(json_encode(["status" => 200]));
    }
    catch (\Exception $e) {
      $this->loggerFactory->get("Docusign")->error($e);
      return new JsonResponse(json_encode(["status" => 500]));
    }
  }
}
