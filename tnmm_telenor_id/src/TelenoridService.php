<?php

namespace Drupal\tnmm_telenor_id;

use \Drupal\Core\Entity\EntityTypeManagerInterface;
use Exception;

class TelenoridService
{

    private $entityTypeManager;

    public function __construct(EntityTypeManagerInterface  $entityTypeManager)
    {
        $this->entityTypeManager = $entityTypeManager;
    }

    public function getToken($code)
    {
        try {

            $config = \Drupal::config('tnmm_telenor_id.settings');
    
    
            $result = \Drupal::httpClient()->post(
                $config->get("host") . '/oauth/token', [
                'verify' => false,
                'form_params' => [
                    'grant_type'=> 'authorization_code',
                    'client_id' => $config->get("client_id"),
                    'redirect_uri'=> $config->get("redirect_uri"),
                    'code' => $code
                ],
    
                  'headers' => [
                    'Content-type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                    'Authorization' =>  'Basic ' . 
                    base64_encode($config->get("client_id") . ':' . $config->get("client_secret"))
            
                  ],
                  'timeout' => $config->get("timeout_oauth_token"),
                ]
            )->getBody()->getContents();
    
            return $result;


        } catch (Exception $e) {

        }      


    }

    public function getUserDetails($access_token)
    {
        try{
            $config = \Drupal::config('tnmm_telenor_id.settings');

            $result = \Drupal::httpClient()->get(
                $config->get("host") . '/oauth/userinfo', [
                'verify' => false,  
                  'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' =>  'Bearer ' . $access_token            
                  ],
                  'timeout' => $config->get("timeout_oauth_userinfo"),
                ]
            )->getBody()->getContents();

            //$profile = json_decode($result, true);

            return $result;
    

        } catch (Exception $e){

        }


    }

}
