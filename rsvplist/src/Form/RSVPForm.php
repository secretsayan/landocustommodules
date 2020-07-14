<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPForm
 */
namespace Drupal\rsvplist\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * RSVP Email form  class.
 */
class RSVPForm extends FormBase
{
    /**
     * (@inheritdoc)
     */
    public function getFormId()
    {
        return "rsvplist_email_form";
    }

    /**
     * (@inheritdoc)
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $node = \Drupal::routeMatch()->getParameter('node');
        $nid = $node->nid->value;
        $form['email'] = array(
            '#title' => t('Email'),
            '#type' => 'textfield',
            '#size' => 25,
            '#description' => t('We will send updates to the email address you provide'),
            "#required" => true,
        );

        $form['submit'] = array(
            '#value' => t('RSVP'),
            '#type' => 'submit',
        );

        $form['nid'] = array(
            '#type' => 'hidden',
            '#value' => $nid,
        );

        return $form;

    }

    /**
     * (@inheritdoc)
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $value = $form_state->getValue('email');
        if (!\Drupal::service('email.validator')->isValid($value)) {
            $form_state->setErrorByName(
                'email', t(
                    'This email address %email is invalid ',
                    array('%email' => $value)
                )
            );
            return;
        }
        $node = \Drupal::routeMatch()->getParameter('node');
        //Check if email is already set for this node.
         $select = Database::getConnection()->select('rsvplist', 'r');
         $select->fields('r', array('nid'));
         $select->condition('nid', $node->id());
         $select->condition("mail", $value);
         $results = $select->execute();
        if (!empty($results->fetchCol())) {
            $form_state->setErrorByName("email", $this->t("The address %mail is already subscribed  to this list", array('%mail' => $value)));
        }

    }

    /**
     * (@inheritdoc)
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        //Loading user data.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

        //Insering in DB.
        db_insert('rsvplist')
            ->fields(
                array(
                "mail" => $form_state->getValue("email"),
                "nid" => $form_state->getValue("nid"),
                "uid" => $user->id(),
                "created" => time()
                )
            )
            ->execute();

        //drupal_set_message(t('The form is working'));
        
        $this->messenger()->addMessage($this->t('Thanks you for your RSVP'));
        

        

    }
}
