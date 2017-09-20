<?php

class ThemeTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Attire
     */
    private $attire;

    /**
     * @var \Attire_theme
     */
    private $theme;    

    protected function setUp()
    {
        $CI =& get_instance();
        $ref = new ReflectionClass($CI->attire);
        $this->attire = $ref->newInstance();
        $this->attire->theme->setPath(TESTPATH.'libraries/Attire/dist/');
        $this->attire->theme->setName('template');        
        parent::setUp();   
    }  

    protected function _before()
    {
        $this->theme = clone $this->attire->theme;
    }

    /**
     * @group theme
     */
    public function testThemeChangePath()
    {
        $path = TESTPATH.'../_data/';
        $this->theme->setName('');
        $this->theme->setPath($path);
        $this->assertEquals(realpath($path).'/', $this->theme->getPath());
    }

    /**
     * @group theme
     */
    public function testThemeIgnoringPath()
    {
        $path = md5('_foo'); # un-existent directory
        $this->theme->setPath($path);
        $this->assertNotEquals($path, $this->theme->getPath());
    }    

    /**
     * @group theme
     */
    public function testThemeSetName()
    {
        $name = 'foo';
        $this->theme->setName($name);
        $this->assertEquals($name, $this->theme->getName());
    }

    /**
     * @group theme
     */
    public function testThemeSetLayout()
    {
        $layout = 'template';
        $result = $this->theme->setLayout($layout);
        $this->assertTrue($result !== FALSE);
    }

    /**
     * @group theme
     */
    public function testThemeSetLayoutWithPath()
    {
        $layout = 'layouts/template';
        $result = $this->theme->setLayout($layout);
        $this->assertTrue($result !== FALSE);        
    }

    /**
     * @group theme
     */
    public function testThemeSetTemplate()
    {
        $template = 'master';
        $path = $this->theme->getPath();
        $this->theme->setTemplate($template);
        $this->assertFileExists($path.$this->theme->getTemplate());
    }
}