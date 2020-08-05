<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\mymodule\HeroArticleService;
use Drupal\Core\Config\ConfigFactory;
/**
 * Main Controller class for this module.
 */
class  MyModuleController extends ControllerBase
{

    private $articleHeroService;
    protected $configFatcory;
    private $current_user;

    /**
     * Create function of ControllerBase.
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('mymodule.hero_articles'),
            $container->get('config.factory'),
            $container->get('current_user')
        );
    } 

    /**
     * Constructor function.
     */
    public function __construct(HeroArticleService $articleHeroService, ConfigFactory $configFactory, $current_user)
    {
        $this->articleHeroService = $articleHeroService;
        $this->configFactory = $configFactory;
        $this->current_user =  $current_user;
    }

    /**
     * Herolist function.
     * 
     * @return Array.
     */
    public function heroList()
    {

       kint($this->articleHeroService->getArticles()); 
        if($this->current_user->hasPermission('can see hero list')) {
            $text = "Hello new page for testing";
        }else{
            $text = "You do not have sufficient permissions to see the list";
        }
        
        return [
            '#theme' => 'heroList',
            '#items' => $text,
            '#title' => $this->configFactory->get("mymodule.settings")->get("mymodule_config_title")
        ];
    }
}
