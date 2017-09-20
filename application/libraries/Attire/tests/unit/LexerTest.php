<?php

class LexerTest extends \Codeception\TestCase\Test
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
     * @var \Attire_lexer
     */
    private $lexer;

    protected function setUp()
    {
        $CI =& get_instance();
        $ref = new ReflectionClass($CI->attire);
        $this->attire = $ref->newInstance();   
        parent::setUp();
    }    

    protected function _before()
    {
        $this->lexer = clone $this->attire->lexer; 
    }

    /*
     * @group filters
     */
    public function testFunctionsAdd()
    {
        // Ruby erb syntax
        $this->lexer->set([
            'tag_comment'  => array('<%#', '%>'),
            'tag_block'    => array('<%', '%>'),
            'tag_variable' => array('<%=', '%>'),
        ]);
        $this->lexer->activate();
        $this->assertInstanceOf('\Twig_Lexer',$this->lexer->get());
    }
}