<?php
/**
 * @file
 * Contains Report Controller Class.
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Controller for RSVP List Report
 */
class ReportController extends ControllerBase
{
    /**
     * Get all RSVPs for all nodes.
     * 
     * @return array
     */
    protected function load()
    {
        $select = Database::getConnection()->select('rsvplist', 'r');

        $select->join('users_field_data', 'u', 'r.uid = u.uid');
        $select->join('node_field_data', 'n', 'r.nid = n.nid');
        $select->addfield('u', 'name', 'username');
        $select->addField('n', 'title');
        $select->addField('r', 'mail');

        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        
        return $entries;
    }
    /**
     * Creates a Report List Page.
     */
    public function report()
    {
        $content = array();
        $content['message'] = array(
            '#markup' => $this->t('List of all RSVPs'),
        );

        $headers = array(
            t('Name'),
            t('Event'),
            t('Email')
        );
        $rows = array();
        foreach ($entries = $this->load() as $entry) {
            $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
        }
        $content['table'] = array(
            '#type' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
            '#empty' => t('No entries available'),
        );

        $content['#cache']['max-age'] = 0;
        return $content;
    }
    
    
}
