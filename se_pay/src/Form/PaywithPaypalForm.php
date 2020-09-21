<?php

namespace Drupal\se_pay\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class to create Paypal payment form.
 */
class PaywithPaypalForm extends FormBase {

  /**
   *
   */
  public function getFormId() {
    return "se_pay.paywithpal";
  }

  /**
   *
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['pReferenceNumber'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Reference number'),
      '#attributes' => ['id' => 'pReferenceNumber'],
    ];

    $form['pCustomerName'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Customer Name'),
      '#attributes' => ['id' => 'pCustomerName'],
    ];

    $form['pCustomerNumber'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Customer Number'),
      '#attributes' => ['id' => 'pCustomerNumber'],
    ];

    $form['pEmailAddress'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email Address'),
      '#attributes' => ['id' => 'pEmailAddress'],
    ];

    $form['pAmount'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Payment Amount'),
      '#attributes' => ['id' => 'pAmount'],
    ];

    $form['paypal-btn'] = [
      '#type' => 'image_button',
      '#name' => 'paypal-btn',
      '#src' => 'https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-medium.png',
      '#value' => $this->t('Pay with Paypal'),
      '#title' => $this->t('Pay With Paypal'),
      '#attributes' => [
        'id' => 'paypal-btn',
        'alt' => $this
          ->t('Check out with PayPal'),
      ],
    ];
    return $form;
  }

  /**
   *
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
  }

}
