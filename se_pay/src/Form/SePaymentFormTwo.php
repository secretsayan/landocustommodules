<?php

namespace Drupal\se_pay\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
//use Drupal\se_checkout\SeCheckoutSessionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SePaymentFormTwo.
 *
 * @package Drupal\se_payment
 */
class SePaymentFormTwo extends FormBase {

  /**
   * The payment session manager.
   *
   * @var \Drupal\se_checkout\SeCheckoutSessionManager
   */
 // protected $checkoutSessionManager;

  /**
   * SePayment constructor.
   *
   * @param \Drupal\se_checkout\SeCheckoutSessionManager $checkout_session_manager
   *   The checkout session manager.
   */

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'se_payment_form_two';
  }



  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
   // $value = $this->checkoutSessionManager->getAccountDetails();
    $customer_number = "12345";
    $reference_number = "23232";

    $form['#attached']['library'][] = 'se_pay/se_payment_bambora';

    $form['#id'] = 'checkout-form';

    $form['reference_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Payment Reference Number'),
      '#default_value' => $reference_number,
      '#required' => TRUE,
      '#attributes' => [
        'readonly' => 'readonly',
      ],
    ];

    $form['customer_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Customer Name'),
      '#default_value' => $customer_number,
      '#required' => TRUE,
      '#attributes' => [
        'readonly' => 'readonly',
      ],
    ];
    $form['name_credit_card'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name on credit card'),
    ];
    $form['card-number'] = [
      '#markup' => '<div class="card-details"><div id ="card-number"> </div>',
    ];
    $form['cvv'] = [
      '#markup' => '<div id ="card-cvv"> </div>
      <div class="tooltip-wrapper"><div>
      <img src="/themes/custom/simplyenergy/assets/images/icons/ui/tooltip.svg" class="tooltip-icon "><span class="tooltip-overlay tooltip-overlay--hidden
      tooltip-align--top" role="tooltip" tabindex="-1" id="react-accessible-tooltip-6" aria-hidden="true" style="left: auto; right: auto;"><div class="box  box-modal undefined"><div><p class="tooltip__text fineprint">The CVC is a 3-digit code that helps keep your online payment secure. It can be found on the back of your credit/debit card on the right side of the white signature strip. For Visa and Mastercard, it is always the last 3 numbers.</p></div></div></span></div></div></div>',
    ];
    $form['expiry_date'] = [
      '#markup' => '<div id ="card-expiry"> </div>',
    ];
    $form['payment_amount'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Payment amount'),
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit Payment'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {



  }

}
