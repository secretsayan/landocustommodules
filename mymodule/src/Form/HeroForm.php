<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class for HeroForm.
 */
class HeroForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        // Instantiates this form class.
        return new static(
        // Load the service required to construct this class.
            $container->get('current_user')
        );
    }
    
    /**
     * Class constructor.
     */
    public function __construct(AccountInterface $account)
    {
        $this->account = $account;
    }



    /**
     * Function to get Form ID.
     * 
     * @return string.
     */
    public function getFormId()
    {
        return 'mymodule.heroform';
    }

    /**
     * Function to buildForm.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['rival_1'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Rival 1')
        );

        $form['rival_2'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Rival 2')
        );

        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Get Winner')
        );
        $form['toggle_me'] = array(
            '#type' => 'checkbox',
            '#title' => t('Tick this box to type'),
          );
        $form['settings'] = array(
            '#type' => 'textfield',
            '#default_value' => $this->account->getAccountName(),
            '#states' => array(
                // Only show this field when the 'toggle_me' checkbox is enabled.
                'visible' => array(
                ':input[name="toggle_me"]' => array(
                    'checked' => true,
                ),
                ),
            ),
        );

          return $form;
    }

    /**
     * Form Submit.
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $winner = rand(1, 2);
        drupal_set_message('The Winner is :' .  $form_state->getValue('rival_' . $winner));
    }
    /**
     * Form Validate.
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        //kint($form_state);die();
        if(empty($form_state->getValue("rival_1"))) {
            $form_state->setErrorByName("rival_1", $this->t("Rival 1 cannot be empty"));
        }
    }
}
