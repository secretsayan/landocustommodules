<?php

namespace Drupal\Tests\mymodule\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\mymodule\Controller\MyModuleController;
use Drupal\mymodule\HeroArticleService;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\DependencyInjection\ContainerBuilder;

class UnitTest extends UnitTestCase
{
    protected $unit;
    /**
     * @inheritdoc
     */

    public function setUp()
    {   
        $this->unit = "Sayan";
    }


    /**
     * @covers Drupal\mymodule\Controller\MyModuleController::heroList
     */
    public function testHeroList()
    {
      /*  $articleService = $this->createMock(HeroArticleService::class);

        $configFactory = $this->createMock(ConfigFactory::class);

        $container = new ContainerBuilder();
    
        \Drupal::setContainer($container);
        $container->set('current_user', $serviceGitCommands);

        $current_user = \Drupal::service("current_user");
        
        $mymod = new MyModuleController($articleService, $configFactory, $current_user);

        $this->assertArrayHasKey('#theme', $mymod->heroList());*/

        $this->assertEquals("Sayan",  $this->unit);

    }

    /**
     * @inheritdoc
     */
    public function teardown()
    {
        unset($this->unit);
        
    }
}
