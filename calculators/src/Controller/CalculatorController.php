<?php

namespace Drupal\calculators\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;
/**
 * Main Controller class for this module.
 */
class CalculatorController extends ControllerBase
{
    /**
     * Home Page Function.
     * 
     * @return Array.
     */
    public function home()
    {
        //kint($this->$articleHeroService->getArticles()); 
        /*
        if ($this->current_user->hasPermission('can see hero list')) {
            $text = "Hello new page for testing";
        } else {
            $text = "You do not have sufficient permissions to see the list";
        }
        */
        
        return [
            '#theme' => 'hometemplate',
            '#title' => 'Home Page'
        ];
    }
}