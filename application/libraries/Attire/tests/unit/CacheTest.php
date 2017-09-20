<?php


class CacheTest extends \Codeception\TestCase\Test
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
     * @var \Attire_cache
     */
    private $cache;    

    protected function setUp()
    {
        $CI =& get_instance();
        $ref = new ReflectionClass($CI->attire);
        $this->attire = $ref->newInstance();   
        parent::setUp();
    }    

    protected function _before()
    {
        $this->cache = clone $this->attire->cache;
        $this->cache->set(TESTPATH.'cache/');
    }

    public function testCacheSet()
    {   
        $response = $this->cache->get() !== FALSE;
        $this->assertTrue($response);
    }

    /**
     * @todo deprecated, test it with another method
     */
    public function testCacheGetFilename()
    {   
        $name = '@theme/'.$this->attire->theme->getTemplate();
        $response = $this->cache->getFilename($name);
        $this->assertFalse($response); # dummy 
        // $output = $this->attire->render(NULL, TRUE);
        // $response = realpath($this->cache->getFilename($name)) !== FALSE; 
        // $this->assertTrue($response);
    }
}