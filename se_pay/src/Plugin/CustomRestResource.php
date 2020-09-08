<?php

namespace Drupal\se_pay\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "se_pay_paypal_success",
 *   label = @Translation("Paypal Success REST resource"), *
 *   uri_paths = {
 *     "canonical" = "/paypal/success",
 *     "https://www.drupal.org/link-relations/create" = "/paypal/success"
 *   }
 * )
 */
class CustomRestResource extends ResourceBase {

  /**
   * Responds to POST requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @param $node_type
   * @param $data
   *
   * @return \Drupal\rest\ResourceResponse Throws exception expected.
   *   Throws exception expected.
   */
  public function post($data) {

    // You must to implement the logic of your REST Resource here.
    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('access content')) {
      throw new AccessDeniedHttpException();
    }

    return new ResourceResponse("sayan");
  }

}
