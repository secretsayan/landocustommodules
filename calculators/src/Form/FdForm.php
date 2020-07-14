<?php

/**
 * Ajax Form File.
 */
namespace Drupal\calculators\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

 /**
  * Class for AjaxForm which extends FormBase
  */
class FdForm extends FormBase
{
    /**
     * Function to get Form ID.
     * 
     * @return String
     */
    public function getFormID()
    {
        return 'calculators.fdform';
    }
    /**
     * Function for Build Form.
     * 
     * @return Array.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['principal'] = array(
              "#type" => "number",
              "#title" => $this->t("Principal Amount"),
              '#required' => true,
              '#default_value' => 500,
              '#min' => 500,
              
        );

        $form['tenure'] = array(
            "#type" => "number",
            "#title" => $this->t("Time Period in Months"),
            '#default_value' => 6,
            '#required' => true,
            '#min' => 6,
            '#max' => 120,
            '#step' =>  1,
        );

        $form['rate'] = array(
            "#type" => "number",
            "#title" => $this->t("Rate of Interest"),
            '#default_value' => 1,
            '#required' => true,
            '#min' => 1,
            '#max' => 10,
            '#step' => 0.1

        );



/*         $form['quantity'] = array(
            '#type' => 'range',
            '#min' => 0,
            '#max' => 10,
            '#title' => $this
              ->t('Quantity'),
            '#default_value' => 1
        ); */

        $form['submit'] = array(
              "#type" => "button",
              "#value" => $this->t("Calculate"),
              "#ajax" => [
                  'callback' => '::setMessage'
              ]

        );

        $form['message'] = array(
            "#type" => "markup",
            "#markup" => "<div class='result'></div>"
        );

        $form['#attached']['library'][] = 'calculators/globalcalc';

          return $form;
    }

    /**
     * Ajax callback function.
     * 
     * @return AjaxResponse.
     */
    public function setMessage(array &$form, FormStateInterface $form_state)
    {
        $principal = $form_state->getValue("principal");
        $tenure = ($form_state->getValue("tenure")/12);
        $rate = $form_state->getValue("rate");
        // compounded quarterly hence using 4
        //fd formula
        $exponent = 4*$tenure;
        $mid = 1+($rate/(100*4));
        $afterpow = pow($mid, ($exponent));
        $amount = $principal * $afterpow;
        
        // rd formula        
        //$n = ($tenure/3);
        //$i = $rate/400;
        //$amount = $principal*(pow((1+$i), $n)-1) / (1 - pow((1 + $i ), (-1/3)) );
        
        
        $response = new AjaxResponse();
        $response->addCommand(
            new HtmlCommand(
                '.result',
                'Maturity Amount: ' .round($amount, 2)
            )
        );

        return $response;

    }


    /**
     * Form Submit.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $principal = $form_state->getValue("principal");
        $tenure = $form_state->getValue("tenure");
        $rate = $form_state->getValue("rate");
        $amount =  $principal*$tenure + (($principal*($tenure)*($tenure + 1)*$rate)/(12*2*100));  
        //$winner = rand(1, 2);
        drupal_set_message('The Winner is at submit :'.$amount );
    }


}
