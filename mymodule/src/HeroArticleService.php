<?php

namespace Drupal\mymodule;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Entity\EntityTypeManager;    
use \Drupal\Core\Entity\EntityTypeManagerInterface; 

/**
 * Class for Article Service.
 */
class HeroArticleService
{

   private $entityTypeManager;

    /**
     * Constructor to initialise the member variables.
     */
    public function __construct( EntityTypeManagerInterface  $entityTypeManager )
    {
        
        $this->entityTypeManager = $entityTypeManager;
    }
    /**
     * Function to getArticles.
     * 
     * @return void
     */
    public function getArticles()
    {

        $values = [
            'type' => 'page',
            ];
            
        return $this->entityTypeManager
            ->getStorage('node')
            ->loadByProperties($values);
    }
}
