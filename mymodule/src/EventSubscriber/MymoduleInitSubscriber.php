<?php
/**
 * Custom Event Subscriber.
 */

 namespace Drupal\mymodule\EventSubscriber;

 use Symfony\Component\EventDispatcher\EventSubscriberInterface;
 use Symfony\Component\HttpKernel\KernelEvents;

 /**
  * Event Subscriber Class
  */
class MymoduleInitSubscriber implements EventSubscriberInterface
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Custom Callback.
     */
    public function onRequest($event)
    {
        drupal_set_message("Hello this is from InitSubscriberss ");
        //$sessid = $_COOKIE['TestCookie'];
        //die();
        //Check if SESSION is set
       /* $database = \Drupal::database();
        $query = $database->select('session_storage', 'u')
            ->condition('u.sid', $sessid)
            ->condition('is_valid','1')
            ->fields('u', ['msisdn', 'expires_in'])
            ->range(0, 1);
        
        $result = $query->execute();
        foreach ($result as $record) {

            drupal_set_message("Hello this is from InitSubscriberss " . print_r($record, true));            
        }*/

            
        
    }

    /**
     * (@inheritdoc)
     */
    public static function getSubscribedEvents()
    {
        $events[KernelEvents::REQUEST][] = "onRequest";

        return $events;

    }

}
