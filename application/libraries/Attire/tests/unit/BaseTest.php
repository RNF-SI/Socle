<?php

class BaseTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @group loader
     */
    public function testLoader()
    {
        $CI =& get_instance(); 
        $this->assertTrue(property_exists($CI, 'attire'));
    }
} 