<?php

class FiltersTest extends \Codeception\TestCase\Test
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
     * @var \Attire_filters
     */
    private $filters;    

    protected function setUp()
    {
        $CI =& get_instance();
        $ref = new ReflectionClass($CI->attire);
        $this->attire = $ref->newInstance();   
        parent::setUp();
    }    

    protected function _before()
    {
        $this->filters = clone $this->attire->filters;
    }

    /*
     * @group filters
     */
    public function testFiltersAdd()
    {
        $this->filters->add('foo',function(){
            return "foo";
        });
        $this->assertInstanceOf('\Twig_SimpleFilter',$this->filters->get('foo'));
    }

}