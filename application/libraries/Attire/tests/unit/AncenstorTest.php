<?php

class AncenstorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Attire
     */
    protected $attire;    

    protected function setUp()
    {
        $this->CI =& get_instance();
        parent::setUp();
    }

    protected function _before()
    {
        $ref = new ReflectionClass($this->CI->attire);
        $this->CI->config->load('attire', TRUE);
        $this->attire = $ref->newInstance($this->CI->config->item('attire'));
    }

    /**
     * @group ancestor
     */    
    public function testChainingCatchException()
    {
        $this->expectException('Attire\Exceptions\AncestorException');
        $this->attire->globals->add('some','value')->lala->bad();
    }

    /**
     * @group ancestor
     */
    public function testChainingCallParentMethod()
    {
        $this->attire->globals->add('some','value')->render();
    }
}