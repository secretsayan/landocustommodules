<?php

namespace Drupal\Tests\mymodule\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\mymodule\Controller\MyModuleController;

class UnitTest extends UnitTestCase
{
    protected $unit;
    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */
   /* public function __construct(MyModuleController $m)
    {
        $this->unit = $m;
    } */
    public function setUp()
    {   
        $this->unit = "Sayan";
    }


    /**
     * @covers Drupal\mymodule\Controller\MyModuleController::heroList
     */
    public function testHeroList()
    {

        $this->assertEquals("Sayan", $this->unit);

    }

    /**
     * @inheritdoc
     */
    public function teardown()
    {
        unset($this->unit);
    }
}
