<?php

namespace Drupal\Tests\mymodule\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\mymodule\HeroArticleService;
use \Drupal\Core\Entity\EntityTypeManagerInterface; 
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\EntityStorageInterface;

class NewTest extends UnitTestCase
{
    public function setUp()
    {
        $container = new ContainerBuilder();
        \Drupal::setContainer($container);
        $node = $this->getMockBuilder(Node::class)
            ->disableOriginalConstructor()
            ->getMock();

        $entityTypeManager = $this->getMockBuilder(EntityTypeManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $node_storage = $this->getMockBuilder(EntityStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $node_storage->expects($this->any())
            ->method('loadByProperties')
            ->willReturn($node);
        $entityTypeManager->expects($this->any())
            ->method('getStorage')
            ->willReturn($node_storage);

        $container->set('entity_type.manager', $entityTypeManager);

    }

    public function testHeroService()
    {   
        
        /*$entityServerSitesMock
        = $this->getMockBuilder(Node::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityServerSitesMock->expects($this->any())
            ->method('id')
            ->will($this->returnValue($this->entityTypeId));

        $entityStorage = $this->getMockBuilder(EntityStorageInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityStorage->expects($this->any())
            ->method('loadloadByProperties')
            ->willReturn($entityServerSitesMock);*/

        $entityTypeManager = \Drupal::service("entity_type.manager");


        $h = new HeroArticleService($entityTypeManager);
        $response = $h->getArticles();
        $this->assertNotEmpty($response);
    }

    public function tearDown()
    {

    }
}
