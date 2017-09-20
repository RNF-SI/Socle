<?php

class ConfigTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \CI_Controller
     */
    private $CI;

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
        $this->attire = $ref->newInstanceWithoutConstructor();
    }

    /*
     * @group config
     */
    public function testConfigDefaultParams()
    {
        $this->CI->config->load('attire', TRUE);
        $params = $this->CI->config->item('attire'); 
        $this->attire->config->set($params);
        $this->assertArraySubset(['environment' => $this->attire->config->get('environment')],$params);
    }
}