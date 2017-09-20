<?php

class RenderTest extends \Codeception\TestCase\Test
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
    private $attire;

    /**
     * Expected output of render method
     */
    const OUTPUT_FILE = 'tests/_data/output.html';

    protected function setUp()
    {
        $this->CI =& get_instance();
        parent::setUp();
    }

    protected function _before()
    {
        $ref = new ReflectionClass($this->CI->attire);
        $this->CI->config->load('attire', TRUE);
        $this->attire = $ref->newInstance();
        $this->attire->theme->setPath(TESTPATH.'libraries/Attire/dist')->setName('template');
    }

    /**
     * @group render
     */
    public function testRender()
    {
      $this->attire->assets->setCache('tests/_data/');
      $file = file_get_contents(self::OUTPUT_FILE);
      $this->expectOutputString($file);
      $this->attire->render();
      echo $this->CI->output->get_output();
    }

    /**
     * @group render
     * @dataProvider exceptionsProvider
     */
    public function testRenderCatchDriverExceptions($driver, $method, array $params, $exception)
    {
      $this->expectException($exception);
      call_user_func_array([$this->attire->{$driver}, $method], $params);
      $this->attire->render();
    }

    /**
     * @group render
     */
    public function testRenderUsingContext()
    {
      $this->attire->views->addPath(TESTPATH.'../_data/views/');
      $output = $this->attire->render('foo', TRUE);
      $this->assertContains('Hello World', $output);
    }

    public function exceptionsProvider()
    {
      return [
        'theme' => ['theme', 'setName', ['foo'], '\Attire\Exceptions\ThemeException']
      ];
    }

    private function _minifyOutput($output)
    {
      $search = array(
          '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
          '/[^\S ]+\</s',  // strip whitespaces before tags, except space
          '/(\s)+/s'       // shorten multiple whitespace sequences
      );

      $replace = array(
          '>',
          '<',
          '\\1'
      );
      return preg_replace($search, $replace, $output);
    }
}
