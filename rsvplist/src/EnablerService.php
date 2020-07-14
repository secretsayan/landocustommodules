<?php

/**
 * @file
 * Contains EnablerService
 */

 namespace Drupal\rsvplist;

 use \Drupal\Core\Database\Database;
 use \Drupal\node\Entity\Node;

 /**
  * Defines a Service for managing nodes.
  */
class EnablerService
{
    /**
     * Constructor of the class.
     */
    function __contsruct()
    {

    }

    /**
     * Sets a individual node to be RSVP enabled.
     */
    public function setEnabled(Node $node)
    {       
        if (!$this->isEnabled($node)) {
            //die("here");
            $insert =  Database::getConnection()->insert('rsvplist_enabled');
            $insert->fields(array("nid"), array($node->id()));
            $insert->execute();            
        }

    }

    /**
     * Function to check if node is enabled.
     */
    public function isEnabled(Node $node)
    {
        if ($node->isNew()) {
            return false;

        }
        $select = Database::getConnection()->select('rsvplist_enabled', 're');
        $select->fields('re', array("nid"));
        $select->condition("nid", $node->id());

        $results = $select->execute();       
     
        return empty($results->fetchAssoc())? false : true;
    }

    /**
     * Deletes a node from rsvplist
     */
    public function delEnabled(Node $node)
    {
        $delete =  Database::getConnection()->delete('rsvplist_enabled');
        $delete->condition('nid', $node->id());
        $delete->execute();           


    }
}
