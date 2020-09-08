<?php

namespace Drupal\se_pay\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Paypal backend Settings Class.
 */
class PaypalSettingsForm extends ConfigFormBase {

  /**
   * (@inheritdoc)
   */
  public function getFormId() {
    return "se_pay_settings_form";
  }

  /**
   * (@inheritDoc)
   *
   * @return void
   */
  public function getEditableConfigNames() {
    return [
      'se_pay.settings',
    ];
  }

  /**
   * (@inheritdoc)
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config("se_pay.settings");

    $form["sessionid"] = [
      "#type" => "textfield",
      "#title" => $this->t("Session ID"),
      "#default_value" => $config->get("sessionid"),
      '#required' => TRUE,
    ];

    $form["username"] = [
      "#type" => "textfield",
      "#title" => $this->t("Username"),
      "#default_value" => $config->get("username"),
      '#required' => TRUE,
    ];

    $form["password"] = [
      "#type" => "textfield",
      "#title" => $this->t("Password"),
      "#default_value" => $config->get("password"),
      '#required' => TRUE,
    ];

    $form["dl"] = [
      "#type" => "textfield",
      "#title" => $this->t("DL"),
      "#default_value" => $config->get("dl"),
      '#required' => TRUE,
    ];

    $form["serverurl"] = [
      "#type" => "textfield",
      "#title" => $this->t("Server URL"),
      "#description" => "Url for the home page",
      "#default_value" => $config->get("serverurl"),
      '#required' => TRUE,
    ];

    $form["userurl"] = [
      "#type" => "textfield",
      "#title" => $this->t("User URL"),
      "#default_value" => $config->get("userurl"),
      '#required' => TRUE,
    ];

    $form["hostippurl"] = [
      "#type" => "textfield",
      "#title" => $this->t("Host IPP URL"),
      "#default_value" => $config->get("hostippurl"),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);

  }

  /**
   * Submit Function for the configuration form.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $sessionid = $form_state->getValue('sessionid');
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');
    $dl = $form_state->getValue('dl');
    $serverurl = $form_state->getValue('serverurl');
    $userurl = $form_state->getValue('userurl');
    $hostippurl = $form_state->getValue('hostippurl');

    $this->config('se_pay.settings')
      ->set('sessionid', $sessionid)
      ->set('username', $username)
      ->set('password', $password)
      ->set('dl', $dl)
      ->set('serverurl', $serverurl)
      ->set('userurl', $userurl)
      ->set('hostippurl', $hostippurl)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
