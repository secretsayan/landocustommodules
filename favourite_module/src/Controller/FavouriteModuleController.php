<?php
/**
 * @file
 * 
 * File for Favourite Module Controller.
 */
namespace Drupal\favourite_module\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller Class.
 */
class FavouriteModuleController extends ControllerBase
{
    /**
     * Stores click on favourite icon.
     * 
     * @return void.
     */
    public function store_favourite_ajax()
    {
         global $language;
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($_POST['user_id'] != null 
                    && $_POST['user_id'] != '' 
                    && $_POST['offer_id'] != null 
                    && $_POST['offer_id'] != ''
                    && $_POST['my_id'] != null 
                    && $_POST['my_id'] != ''
                ) {
                    $favInfo = [
                    'user_id' => $_POST['user_id'], 
                    'node_id' => $_POST['offer_id'], 
                    'my_node_id' => $_POST['my_id']
                    ];

                    $favouritedOffer = db_insert('user_offers')
                        ->fields($favInfo)
                        ->execute();

                    if($favouritedOffer) {
                        return drupal_json_output(['status' => 'success']);
                    }else{
                        return drupal_json_output(['status' => 'fail']);
                    }
                } else {
                    return drupal_json_output(['status' => 'error', 'msg' => 'Invalid data']);
                }
                
            } else {
                if ($language->language == 'en') {
                    header('Location: https://www.telenor.com.mm/en/405');
                } else {
                    header('Location: https://www.telenor.com.mm/my/405');                
                }
              
            }
        } else {
            if ($language->language == 'en') {
                header('Location: https://www.telenor.com.mm/en/405');
            } else {
                header('Location: https://www.telenor.com.mm/my/405');                
            }
        }
        
        //  $result = db_select("MyGuests", "mgs")
        //           ->fields("mgs")
        //           ->condition("type", $_POST['offer_id'])
        //           ->execute()
        //           ->fetchAll();

        // if(count($result) > 0) {
        //    var_dump($result[0]->type);
        //    $updatetype = $result[0]->type;
        //    $updatequery = db_update("MyGuests", "mgs")
        //                   ->expression("count", "count + 1")
        //                   ->condition("type", $updatetype)
        //                   ->execute();
        // }

        // $query = db_query("select * from MyGuests WHERE type=".$_POST['offer_id']);
        // $result = $query->fetchAll();

        // if(count($result) > 0) {
        //    var_dump($result[0]->type);

        //    $updatetype = $result[0]->type;
        //    $updatequery = db_query("UPDATE MyGuests SET count=count+1 WHERE type=".$updatetype);
        // }
        //else {
          //$insertfields =  ['type' => $_POST["offer_id"], 'count' => 1];
          //$insertquery = db_insert("MyGuests")->fields($insertfields)->execute();
        //}
    }
    /**
     * Function for calling kml.
     * 
     * @return void.
     */
    public function call_kml()
    {
        $region = $_POST['region'];
        $networktype = $_POST['networktype'];
        for ($i=0; $i < count($networktype); $i++) { 

            for ($y=0; $y < count($region); $y++) { 
      
                $dir = "/var/www/html/".$networktype[$i]."/".$region[$y]."Classified/";
                $count =  (count(scandir($dir)) - 2)-1 ;
                $url = $networktype[$i]."/".$region[$y]."Classified/";

                for ($x=0; $x < $count ; $x++) { 

                    $data['data']['img'][] = $url.'0/'.$x.'.png';
                    $kml  = $dir.$x.".kml";

                    $xml=simplexml_load_file($kml) or die("Error: Cannot create object");
      

                    $array = json_decode(json_encode($xml, 1));

                    // var_dump($array->Document->Region->LatLonAltBox->north);

                    $post['north'] = $array->Document->Region->LatLonAltBox->north;
                    $post['south'] =  $array->Document->Region->LatLonAltBox->south;
                    $post['east']  = $array->Document->Region->LatLonAltBox->east;
                    $post['west'] = $array->Document->Region->LatLonAltBox->west;

                    // $location['LatLonAltBox'][] = $post;
                    $data['data']['located'][] = $post;
                }
            }
        }

          return drupal_json_output($data);
    }
    /**
     * Removes a count from favourite icon.
     * 
     * @return void.
     */
    public function destroy_favourite_ajax()
    {
         global $language;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($_POST['user_id'] != null 
                    && $_POST['user_id'] != '' 
                    && $_POST['offer_id'] != null 
                    && $_POST['offer_id'] != ''
                    && $_POST['my_id'] != null
                    && $_POST['my_id'] != ''
                ) {
                    $favInfo = [
                    'user_id' => $_POST['user_id'], 
                    'node_id' => $_POST['offer_id'], 
                    'my_node_id' => $_POST['my_id']
                    ];

                     $result =  db_delete('user_offers')
                         ->condition('user_id', $favInfo['user_id'])
                         ->condition('node_id', $favInfo['node_id'])
                         ->condition('my_node_id', $favInfo['my_node_id'])
                         ->execute();

                    if ($result) {
                        return drupal_json_output(['status' => 'success']);
                    } else {
                        return drupal_json_output(['status' => 'fail']);
                    }
                } else {
                    return drupal_json_output(['status' => 'error', 'msg' => 'Invalid data']);
                }
                
            } else {
                if ($language->language == 'en') {
                    header('Location: https://www.telenor.com.mm/en/405');
                } else {
                    header('Location: https://www.telenor.com.mm/my/405');                
                }
            }
        } else {
            if ($language->language == 'en') {
                header('Location: https://www.telenor.com.mm/en/405');
            } else {
                header(
                    'Location: https://www.telenor.com.mm/my/405'
                );                
            }
        }
        // $favInfo = ['user_id' => $_POST['user_id'], 'node_id' => $_POST['offer_id']];
        // $result =  db_delete('user_offers')
        //               ->condition('user_id', $favInfo['user_id'])
        //               ->condition('node_id', $favInfo['node_id'])
        //               ->execute();

        // $result = db_select("MyGuests", "mgs")
        //           ->fields("mgs")
        //           ->condition("type", $_POST['offer_id'], "=")
        //           ->execute()
        //           ->fetchAll();

        // if(count($result) > 0) {
        //    var_dump($result[0]->type);
        //    $updatetype = $result[0]->type;
        //    $updatequery = db_update("MyGuests", "mgs")
        //                   ->expression("count", "count - 1")
        //                   ->condition("type", $updatetype)
        //                   ->execute();
        // }
        // ("UPDATE MyGuests SET count=count-1 WHERE type=".$updatetype);
        // favourite_module_index();
        // return drupal_json_output(['status' => 'success']);
    }

    /**
     * Call API to get Promo Code.
     * 
     * @return JSON
     */
    public function get_promo()
    {

        $ShopName = $_POST['ShopName'];
        $soap_request  = "<?xml version=\"1.0\"?>\n";
         $soap_request .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lms="http://www.telenor.com.mm/LMSCustomerDiscountCoupons" xmlns:com="http://xmlns.telenor.com.mm/Schema/Common/1.0/Common.xsd">
               <soapenv:Header/>
               <soapenv:Body>
                  <lms:GetDiscountCouponsRequest>
                     <!--Optional:-->
                     <com:TransactionReference>
                        <!--Optional:-->
                        <com:SourceSystemId>WEB_SELF_CARE</com:SourceSystemId>
                        <!--Optional:-->
                        <com:IdRef>1211</com:IdRef>
                     </com:TransactionReference>
                     <!--Optional:-->
                     <lms:GetDiscountCouponsReq>
                        <lms:MSISDN>'.variable_get("sessionLRV")['msisdn'].'</lms:MSISDN>
                        <lms:PartnerName>'.$ShopName.'</lms:PartnerName>
                        <!--Optional:-->
                        <lms:ConnType></lms:ConnType>
                     </lms:GetDiscountCouponsReq>
                  </lms:GetDiscountCouponsRequest>
               </soapenv:Body>
            </soapenv:Envelope>';
             
              $header = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "Content-length: ".strlen($soap_request),
              );
             
              $soap_do = curl_init();
              curl_setopt($soap_do, CURLOPT_URL, "http://esbfuseflexi.telenor.com.mm:8011/cxf/LMSCustomerDiscountCouponsSyncPS?wsdl");
              curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
              curl_setopt($soap_do, CURLOPT_TIMEOUT,        10);
              curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
              curl_setopt($soap_do, CURLOPT_POST,           true);
              curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $soap_request);
              curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);

              $xml = curl_exec($soap_do);

              //parse the string between <return> tags
              // dd($xml);
              $cutlms_xml = str_replace("lms:", "", $xml);
              $cutcom_xml = str_replace("com:", "", $cutlms_xml);
              
              //string cut function
              $string = $cutcom_xml;
              $start  = "<GetDiscountCouponsResponse>";
              $end    = "</GetDiscountCouponsResponse>";
              $string = ' ' . $string;
              $ini = strpos($string, $start);
              if ($ini == 0) { 
                  return '';
              }
              $ini += strlen($start);
              $len = strpos($string, $end, $ini) - $ini;

              $parsed = "<return>".substr($string, $ini, $len)."</return>";;
              $ob= simplexml_load_string($parsed);
              $json  = json_encode($ob);

              $result = json_decode($json, true);
                 // return $result;


              if ($result!=null) {

                  $query = db_select('node', 't2')
                      ->fields('t2')
                      ->condition('t1.field_offer_code_no_value', $result['GetDiscountCouponsResp']['PartnerName'])
                      ->condition('t2.language', 'en');

                  $query->join('field_data_field_offer_code_no', 't1', 't1.entity_id = t2.nid');
                  $existingCoupon = $query->execute()->fetchAssoc();

                  $coupon = [
                  'msisdn' => $result['GetDiscountCouponsResp']['MSISDN'], 
                  'coupon_code' => $result['GetDiscountCouponsResp']['CouponCode'], 
                  'provider_name' => $result['GetDiscountCouponsResp']['PartnerName'],
                  'provider_full_name' => $existingCoupon['title'],
                  'created_at' => date('Y-m-d H:i:s')
                  ];

                  $favouritedOffer = db_insert('coupon_history')
                      ->fields($coupon)
                      ->execute();

                  return drupal_json_output($result);

              } else {
                  return drupal_json_output(['status' => 'fail']);
              }
    }    

}
