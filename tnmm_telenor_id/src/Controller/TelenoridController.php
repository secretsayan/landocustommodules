<?php

namespace Drupal\tnmm_telenor_id\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\tnmm_telenor_id\TelenorIdService;
use Drupal\Core\Url;
use Drupal\Core\Database\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TelenoridController extends ControllerBase
{
    private $telenorIdService;

    /**
     * The database connection.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $connection;
    /**
     * Create function of ControllerBase.
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('tnmm_telenor_id.apiService'),
            $container->get('database')
        );
    }

    /**
     * Constructor function.
     */
    public function __construct(TelenorIdService $telenorIdService, Connection $connection)
    {
        $this->telenorIdService = $telenorIdService;
        $this->connection = $connection;
    }

    public function authorize()
    {
        try {

            $state = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10);

            $entry = [
                "state" => $state,
                "created_at" => time(),
                "status" => 0
            ];


            $return_value = $this->connection->insert('telenorid_state_values')
                ->fields($entry)
                ->execute();


            $config = \Drupal::config('tnmm_telenor_id.settings');

            $url = $config->get('host') . "/oauth/authorize" . "?client_id=" . urlencode($config->get('client_id')) . "&redirect_uri=" . urlencode($config->get('redirect_uri')) . "&response_type=code&ui_locales=my+en" . "&scope=" . urlencode($config->get('scope')) . "&state=$state";

            return new TrustedRedirectResponse($url);
        } catch (\Exception $e) {
            return new Response("Error" . $e);

            //$response = new RedirectResponse('/user');
            //$response->send();
            //return;
        }
    }

    public function authenticate()
    {
        try {
            $code = \Drupal::request()->query->get('code');
            $state = \Drupal::request()->query->get('state');

            // Verify state.
            $stateDB = $this->connection->select("telenorid_state_values", "t")
                ->fields("t", ["state"])
                ->condition('state', $state)
                ->condition('status', 0)
                ->condition('created_at', time() - 3600, '>=')
                ->execute()
                ->fetchObject();


            if ($state != $stateDB->state) {
                return new Response("Errorstate" . $state . " state " . print_r($stateDB, 1));
            } else {
                $count = $this->connection->update('telenorid_state_values')
                    ->fields(["status" => 1])
                    ->condition('state', $state)
                    ->execute();
            }

            $result = $this->telenorIdService->getToken($code);

            $res = json_decode($result, true);
            $response = json_decode($result);

            $userDetails = $this->telenorIdService
                ->getUserDetails($response->access_token);

            $user = json_decode($userDetails, true);
            $msisdn = $user["phone_number"];

            //Check to see user exists , if exists login and update timestamp else create user.

            $user_loaded = user_load_by_name($msisdn);

            if ($user_loaded == false) {

                $newuser = \Drupal\user\Entity\User::create();

                // Mandatory.
                $newuser->setPassword('password');
                $newuser->enforceIsNew();
                $newuser->setUsername($msisdn);

                // Optional.
                $newuser->set('field_msisdn', $msisdn);
                $newuser->addRole('end_user');
                $newuser->activate();

                // Save user account.
                $result = $newuser->save();

                //log in user
                user_login_finalize($newuser);
                $_SESSION['msisdn'] = $msisdn;
            } else {
                // log in user
                user_login_finalize($user_loaded);
                $_SESSION['msisdn'] = $msisdn;
            }

            $response = new RedirectResponse('/user');
            $response->send();
            return;
        } catch (\Exception $e) {
            return new Response("Error" . $e->getMessage());
        }
    }


    public function telenorLogout()
    {
        try {

            $config = \Drupal::config('tnmm_telenor_id.settings');

            // Telenorid logout
            if (isset($_SESSION['msisdn'])) {

                // Prepare query string
                $queryStrings = [
                    'client_id'                 => $config->get('client_id'),
                    'post_logout_redirect_uri'    => $config->get('post_logout_redirect_uri')
                ];

                $params = array_values($queryStrings);
                foreach (array_keys($queryStrings) as $key => $value) {
                    $keys[] = "$value=$params[$key]";
                }
                $keys = implode('&', $keys);
                $url = $config->get('host') . '/oauth/logout?' . $keys;
                return new TrustedRedirectResponse($url);

            } else {
                user_logout();

                $response = new RedirectResponse('/');
                $response->send();
                return;
            }
        } catch (\Exception $e) {
           
        }
    }

    public function ensureLogout()
    {
        user_logout();

        $response = new RedirectResponse('/');
        $response->send();
        return;
    }
}
