<?php

namespace Drupal\mymodule;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityManager;    

/**
 * Class for Article Service.
 */
class HeroArticleService
{

    private $entityQuery;
    private $entityManager;

    /**
     * Constructor to initialise the member variables.
     */
    public function __construct(QueryFactory $entityQuery, EntityManager $entityManager)
    {
        
        $this->entityManager = $entityManager;
        $this->entityQuery = $entityQuery;
    }
    /**
     * Function to getArticles.
     * 
     * @return void
     */
    public function getArticles()
    {

        $nids =  $this->entityQuery
            ->get('node')
            ->condition('type', 'article')
            ->execute();
            
        return $this->entityManager->getStorage('node')->loadMultiple($nids);
    }
}
