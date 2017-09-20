<?php

class FunctionsTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Attire
     */
    protected $attire;

    /**
     * @var \Attire_functions
     */
    private $functions;

    protected function setUp()
    {
        $CI =& get_instance();
        $ref = new ReflectionClass($CI->attire);
        $this->attire = $ref->newInstance();   
        parent::setUp();
    }    

    protected function _before()
    {
        $this->functions = clone $this->attire->functions;
    }

    /*
     * @group filters
     */
    public function testFunctionsAdd()
    {
        $this->functions->add('foo',function(){
            return "foo";
        });
        $this->assertInstanceOf('\Twig_SimpleFunction',$this->functions->get('foo'));
    }

}