<?php

/**
 * Custom Config Form File.
 */

 namespace Drupal\mymodule\Form;

 use Drupal\Core\Form\ConfigFormBase;
 use Drupal\Core\Form\FormStateInterface;

 /**
  * Class for ConfigForm.
  */
class ConfigForm extends ConfigFormBase
{
    /**
     * (@inheritdoc)
     */
    public function getFormID()
    {
        return "mymodule.configform";
    }
    /**
     * (@inheritdoc)
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config("mymodule.settings");
        $form["mymodule_config_title"] =  array(
            "#type" => "textfield",
            "#title" => $this->t("Please enter title"),
            "#default_value" => $config->get("mymodule_config_title")
        );



        return parent::buildForm($form, $form_state);

    }
    /**
     * (@inheritdoc)
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $config = $this->configFactory()->getEditable("mymodule.settings");
        $config
            ->set("mymodule_config_title", $form_state->getValue("mymodule_config_title"))
            ->save();

        parent::submitForm($form, $form_state);
    }
     /**
      * (@inheritdoc)
      */
    public function getEditableConfigNames()
    {
        return "mymodule.settings";
    }  
}
