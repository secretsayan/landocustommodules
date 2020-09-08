<?php

namespace Drupal\se_pay\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * This provides a block called "Example Hero Block".
 *
 * @Block(
 * id= "se_pay_paypal",
 * admin_label=@Translation("Paypal Form Block")
 * )
 */
class PaypalBlock extends BlockBase {

  /**
   *
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\se_pay\Form\PaywithPaypalForm');
    $renderArray['form'] = $form;
    $renderArray['#attached'] = [
      'library' => ['se_pay/payment'],
    ];

    return $renderArray;
  }

}
