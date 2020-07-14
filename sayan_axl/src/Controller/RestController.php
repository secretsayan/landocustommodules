<?php

namespace Drupal\sayan_axl\Controller;

use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;
use Drupal\node\NodeInterface;

/**
 * Controller class for json representation.
 */
class RestController
{

    /**
     * Function to get response.
     */
    public function getContent($apikey, $id)
    {

        $node = Node::load($id);
        $site_api_key = \Drupal::config('core.site_information')->get('siteapikey');

        // Check to see node is type "basic page" and "apikey" entered is correct
        if ($node instanceof  NodeInterface && $node->getType() == "page" && $apikey == $site_api_key) {

            $serializer = \Drupal::service('serializer');
            $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);

        } else {
            $data = "Access Denied";
        }

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

}
