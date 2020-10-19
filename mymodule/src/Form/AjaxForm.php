<?php

/**
 * Ajax Form File.
 */
namespace Drupal\mymodule\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

 /**
  * Class for AjaxForm which extends FormBase
  */
class AjaxForm extends FormBase
{
    /**
     * Function to get Form ID.
     *
     * @return String
     */
    public function getFormID()
    {
        return 'mymodule.ajaxform';
    }
    /**
     * Function for Build Form.
     *
     * @return Array.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['message'] = array(
            "#type" => "markup",
            "#markup" => "<div class='result'></div>"
        );
          $form['rival_1'] = array(
              "#type" => "textfield",
              "#title" => $this->t("Rival 1")
          );

          $form['rival_2'] = array(
            "#type" => "textfield",
            "#title" => $this->t("Rival 2")
          );

          $form['submit'] = array(
              "#type" => "button",
              "#value" => $this->t("submit"),
              "#ajax" => [
                  'callback' => '::setMessage'
              ]

            );
          return $form;
    }

    /**
     * Ajax callback function.
     *
     * @return AjaxResponse.
     */
    public function setMessage(array &$form, FormStateInterface $form_state)
    {
        $winner = rand(1, 2);
        $response = new AjaxResponse();

      if(empty($form_state->getValue('rival_1')) || empty($form_state->getValue('rival_2')) ) {

        $response->addCommand(
          new HtmlCommand(
            '.result',
            'Insufficient input' . $form_state->getValue("rival_" . $winner)
          )
        );

      } else {

        $response->addCommand(
          new HtmlCommand(
            '.result',
            'The winner is ' . $form_state->getValue("rival_" . $winner)
          )
        );

      }

        return $response;

    }

  /**
   * Form Submit.
   * @param array $form
   * @param FormStateInterface $form_state
   */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $winner = rand(1, 2);

       //if(empty($form_state->getValue('rival_1')) || empty($form_state->getValue('rival_2')) ){
         \Drupal::messenger()->addError(t('Insufficient input'));
       //} else {
        // \Drupal::messenger()->addMessage(t("'The Winner is :' .  $form_state->getValue('rival_' . $winner)"));
       //}

    }


}
