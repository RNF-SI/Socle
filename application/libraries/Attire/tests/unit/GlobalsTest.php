<?php

class GlobalsTest extends \Codeception\TestCase\Test
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
     * @var \Attire_globals
     */
    private $globals;    

    protected function setUp()
    {
        $CI =& get_instance();
        $ref = new ReflectionClass($CI->attire);
        $this->attire = $ref->newInstance();   
        parent::setUp();
    }    

    protected function _before()
    {
        $this->globals = clone $this->attire->globals;
    }

    /**
     * @group globals
     */
    public function testGlobalChaining()
    {
        $globals = $this->globals->add('foo','bar');
        $views   = $globals->views->add('foo');
        $this->assertInstanceOf('Attire_views', $views);
    }

    /**
     * @group globals
     */
    public function testGlobalGet()
    {
        $this->globals->add('foo','bar');
        $this->assertArrayHasKey('foo', $this->globals->get());
    }

    /**
     * @group globals
     */
    public function testGlobalMerge()
    {
        $context = $this->globals->merge(['some' => 'other']);
        $this->assertArraySubset($this->globals->get(), $context);
    } 

    /**
     * @group globals
     */
    public function testGlobalInheritTwigParentMethods()
    {
        $this->attire->environment->addGlobal('foo', 'fighters');
        $this->assertNotEmpty($this->globals->get());
    }

}