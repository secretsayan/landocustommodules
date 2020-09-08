<?php

/**
 * @file
 * Contains Drupal\tnmm_telenor_id\Form\TelenoridSettingsForm
 */

namespace Drupal\tnmm_telenor_id\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Telenor id backend Settings Class.
 */
class TelenoridSettingsForm extends ConfigFormBase
{
    /**
     * (@inheritdoc)
     */
    public function getFormId()
    {
        return "tnmm_telenor_id_settings_form";
    }

    /**
     * (@inheritDoc)
     *
     * @return void
     */
    public function getEditableConfigNames()
    {
        return [
            'tnmm_telenor_id.settings'
        ];
    }

    /**
     * (@inheritdoc)
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return void
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config("tnmm_telenor_id.settings");

        $form["host"] = array(
            "#type" => "textfield",
            "#title" => "Host",
            "#description" => "Telenor ID Host name",
            "#default_value" => $config->get("host")
        );

        $form["client_id"] = array(
            "#type" => "textfield",
            "#title" => "Client ID",
            "#description" => "Client ID provided by Telenor Digital Team",
            "#default_value" => $config->get("client_id")
        );

        $form["client_secret"] = array(
            "#type" => "textfield",
            "#title" => "Client Secret",
            "#description" => "Client Secret provided by Telenor Digital Team",
            "#default_value" => $config->get("client_secret")
        );

        $form["home_url"] = array(
            "#type" => "textfield",
            "#title" => "Home URL",
            "#description" => "Url for the home page",
            "#default_value" => $config->get("home_url")
        );

        $form["redirect_uri"] = array(
            "#type" => "textfield",
            "#title" => "Redirect URI",
            "#description" => "Redirect UI",
            "#default_value" => $config->get("redirect_uri")
        );

        $form["scope"] = array(
            "#type" => "textfield",
            "#title" => "Scope",
            "#description" => "Scope",
            "#default_value" => $config->get("scope")
        );

        $form["login_redirect"] = array(
            "#type" => "textfield",
            "#title" => "Login Redirect",
            "#description" => "Login redirect URL resgistered while cretaing client in Telenor ID",
            "#default_value" => $config->get("login_redirect")
        );

        $form["logout_redirect"] = array(
            "#type" => "textfield",
            "#title" => "Logout Redirect",
            "#description" => "Logout redirect URL resgistered while cretaing client in Telenor ID",
            "#default_value" => $config->get("logout_redirect")
        );

        $form["post_logout_redirect_uri"] = array(
            "#type" => "textfield",
            "#title" => "Post Logout Redirect",
            "#description" => "Post Logout redirect URL resgistered while cretaing client in Telenor ID",
            "#default_value" => $config->get("post_logout_redirect_uri")
        );

        $form["timeout_oauth_token"] = array(
            "#type" => 'number',
            "#title" => "Timeout oauth/token API",
            "#description" => "Time out setting",
            "#default_value" => $config->get("timeout_oauth_token")
        );

        $form["timeout_oauth_userinfo"] = array(
            "#type" => "number",
            "#title" => "Timeout oauth/userinfo API",
            "#description" => "Post Logout redirect URL resgistered while cretaing client in Telenor ID",
            "#default_value" => $config->get("timeout_oauth_userinfo")
        );



        return parent::buildForm($form, $form_state);

    }

    /**
     * Submit Function for the configuration form.
     *
     * @param array $form
     * @param FormStateInterface $form_state
     * @return void
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $host = $form_state->getValue('host');
        $client_id = $form_state->getValue('client_id');
        $client_secret = $form_state->getValue('client_secret');
        $home_url = $form_state->getValue('home_url');
        $redirect_uri = $form_state->getValue('redirect_uri');
        $scope = $form_state->getValue('scope');
        $login_redirect = $form_state->getValue('login_redirect');
        $logout_redirect = $form_state->getValue('logout_redirect');
        $post_logout_redirect_uri = $form_state->getValue('post_logout_redirect_uri');
        $timeout_oauth_userinfo = $form_state->getValue('timeout_oauth_userinfo');
        $timeout_oauth_token = $form_state->getValue('timeout_oauth_token');

        $this->config('tnmm_telenor_id.settings')
            ->set('host', $host)
            ->set('client_id', $client_id)
            ->set('client_secret', $client_secret)
            ->set('home_url', $home_url)
            ->set('redirect_uri', $redirect_uri)
            ->set('scope', $scope)
            ->set('login_redirect', $login_redirect)
            ->set('logout_redirect', $logout_redirect)
            ->set('post_logout_redirect_uri', $post_logout_redirect_uri)
            ->set('timeout_oauth_userinfo', $timeout_oauth_userinfo)
            ->set('timeout_oauth_token', $timeout_oauth_token)
            ->save();
            parent::submitForm($form, $form_state);
    }
}
