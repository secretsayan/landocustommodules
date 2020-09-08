<?php

namespace Drupal\se_pay;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\Client;
use SoapClient;

/**
 * Payment Service Class.
 */
class PaymentService {

  /**
   * Config faactory data member.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFatory;

  /**
   * HTTP client.
   *
   * @var \var\GuzzleHttp\Client
   */
  private $httpClient;

  /**
   * Constructor function for this class.
   */
  public function __construct(ConfigFactoryInterface $configFatory, Client $httpClient) {
    $this->configFactory = $configFatory;
    $this->httpClient = $httpClient;
  }

  /**
   * Function to get token from IPP.
   */
  public function getIppToken($params) {
    $config = $this->configFactory->get('se_pay.settings');

    $initializePage = $config->get('hostippurl') . "?" . http_build_query($params);

    \Drupal::logger('Paypal')->info("Request: curl 1 " . $initializePage);
    $result = $this->httpClient->post(
        $initializePage,
        [
          'verify' => FALSE,
          'headers' => [
            'Content-type' => 'application/x-www-form-urlencoded',
            'cache-control' => 'no-cache',

          ],

        ]
    )->getBody()->getContents();

    return $result;
  }

  /**
   * Function for CreditCard Payment Soap Call.
   */
  public function doCreditCardPayment($params) {

    \Drupal::logger("Credit card")->info(print_r($params,TRUE));

$payload = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:dts="http://www.ippayments.com.au/interface/api/dts">
<soapenv:Header/>
<soapenv:Body>
   <dts:SubmitSinglePayment>
      <!--Optional:-->
      <dts:trnXML>
    <![CDATA[
      <Transaction>
               <AccountNumber>' .$params["AccountNumber"]. '</AccountNumber>
              <CustRef>' . $params["CustRef"] . '</CustRef>
              <CustNumber>' .$params["CustNumber"]. '</CustNumber>
              <Amount>10000</Amount>
              <TrnType>1</TrnType>
              <CreditCard>
                <SecureTransactionToken>'. $params["stt"] . '</SecureTransactionToken>

              </CreditCard>
               <Security>
                   <UserName>' . $params["UserName"] . '</UserName>
                   <Password>' .$params["Password"]. '</Password>
               </Security>
<AdditionalReturnValues>
      <CardType>true</CardType>
      <TruncatedCard>true</TruncatedCard>
      <CardHolderName>true</CardHolderName>
      <ExpM>true</ExpM>
      <ExpY>true</ExpY>
</AdditionalReturnValues>
             </Transaction>


     ]]>
      </dts:trnXML>
   </dts:SubmitSinglePayment>
</soapenv:Body>
</soapenv:Envelope>';


$header = array(
  "Content-type: text/xml;charset=\"utf-8\"",
  "Content-length: " . strlen($payload),
);




$xml = $this->httpClient->post(
  'https://demo.bambora.co.nz/interface/api/dts.asmx',
  [
    'verify' => FALSE,
    'headers' => [
      'Content-type' => 'text/xml',
      'cache-control' => 'no-cache',
    ],
    'body' => $payload

  ]
)->getBody()->getContents();

$xmlobj = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
$array_data = strip_tags($xmlobj);

$response["ResponseCode"] = $this->getParsedData($array_data,"ResponseCode");

if($response["ResponseCode"] == '0'){

  $data_required = ['CardType','CardHolderName','Receipt','SettlementDate','TruncatedCard','ExpM','ExpY','Timestamp'];

  foreach($data_required as $value){
    $response[$value] = $this->getParsedData($array_data,$value);
  }

}else{
  $data_required = ['DeclinedMessage','DeclinedCode','Timestamp'];

  foreach($data_required as $value){
    $response[$value] = $this->getParsedData($array_data,$value);
  }
}



return $response;
}

public function getParsedData($array_data,$key){
  return substr($array_data, strpos($array_data,$key) + strlen("$key>") + 3, (strrpos($array_data, $key) - strpos($array_data,$key) - strlen("</$key>") - 6 ));

}
}
