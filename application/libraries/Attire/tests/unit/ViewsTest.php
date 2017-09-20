<?php

class ViewsTest extends \Codeception\TestCase\Test
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
     * @var \Attire_views
     */
    private $views;

    protected function setUp()
    {
        $CI =& get_instance();
        $ref = new ReflectionClass($CI->attire);
        $this->attire = $ref->newInstance();
        parent::setUp();
    }

    protected function _before()
    {
        $this->views = clone $this->attire->views;
    }

    /**
     * @group views
     */
    public function testViewsAddLoaderPath()
    {
        $path = rtrim(TESTPATH,'/');
        $this->views->addPath($path);
        $this->assertContains($path, $this->views->getPaths());
    }

    /**
     * @group views
     */
    public function testViewsPrependLoaderPath()
    {
        $path = rtrim(TESTPATH,'/');
        $this->views->prependPath($path);
        $this->assertEquals($path, current($this->views->getPaths()));
    }

    /**
     * @group views
     */
    public function testViewsReset()
    {
        $this->views->add('home.html', ['foo' => 'bar']);
        $this->views->reset();
        $this->assertEmpty($this->views->get());
    }

    /**
     * @group views
     */
    public function testViewsRemove()
    {
        $view = 'home.html';
        $this->views->add($view);
        $this->views->remove($view);
        $this->assertNotContains($view, $this->views->get());
    }
}
