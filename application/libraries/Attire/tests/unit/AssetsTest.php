<?php

class AssetsTest extends \Codeception\TestCase\Test
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
     * @var \Attire_assets
     */
    private $assets;

    protected function setUp()
    {
        $CI =& get_instance();
        $ref = new ReflectionClass($CI->attire);
        $this->attire = $ref->newInstance();      
        parent::setUp();
    }

    protected function _before()
    {
        $this->assets = clone $this->attire->assets;     
    }

    /**
     * @group assets
     * @dataProvider chainingProvider
     */
    public function testAssetsChaining($driver, $method, $params, $expected)
    {
        $assets = $this->assets->addPath('_foo/bar');
        $chain = call_user_func_array([$assets->{$driver}, $method], $params);
        $this->assertInstanceOf($expected, $chain);
    }

    /**
     * @group assets
     * @dataProvider pathsProvider
     */
    public function testAssetPaths($dir, $append = TRUE)
    {
        (! $append) && $this->assets->prependPath($dir) || $this->assets->addPath($dir);
        $paths = $this->assets->getPaths();
        $this->assertContains($dir, $paths);      
    }

    /**
     * @group assets
     * @dataProvider cacheProvider
     */
    public function testAssetsCache($dir, $expected)
    {
        $this->assets->setCache($dir);
        $this->assertEquals($expected, $this->assets->cacheActive());
    }    

    /**
     * @group assets
     */
    public function testAssetsUnsetCache()
    {
        $dir = 'tests/_data/';
        $this->assets->setCache($dir);
        $this->assets->unsetCache();
        $this->assertFalse($this->assets->cacheActive());
    }

    /**
     * @group assets
     * @dataProvider prefixesProvider
     */
    public function testAssetsPrefixes($type, $prefix)
    {
        $this->assets->setPrefix($type, $prefix);
        $this->assertEquals($prefix, $this->assets->getPrefix($type));
        $prefixes = $this->assets->getPrefixes();
        $this->assertEquals($type, $prefixes[$type]);
    }

    /**
     * @group assets
     */
    public function testSetExternalPaths()
    {
        $this->assets->setPaths([
            'path/to/foo',
            'path/to/bar/',
        ]);
        foreach ($this->assets->getPaths() as $path) 
        {
            $this->assertEquals('/', substr($path, -1));
        }
    }    

    /**
     * @group assets
     */
    public function testSetManifest()
    {
        $manifest = 'some';
        $this->assets->setManifest($manifest);
        $this->assertEquals($manifest, $this->assets->getManifest());
    }

    /**
     * @group assets
     */
    public function testAddManifestPaths()
    {
        $manifest = 'path/to/foo/';
        $this->assets->addManifestPath($manifest);
        $this->assertContains($manifest, $this->assets->getManifestPaths());
    } 

    /**
     * @group assets
     */
    public function testSetManifestPaths()
    {
        $manifests = ['path/to/foo/'];
        $this->assets->setManifestPaths($manifests);
        foreach ($manifests as $manifest) 
        {
            $this->assertContains(rtrim($manifest,'/').'/', $this->assets->getManifestPaths());
        }
    }           

    public function prefixesProvider()
    {
        return [
            ['css','css'],
        ];
    }

    public function chainingProvider()
    {
        return [
            'Cache'   => ['cache','set', ['_foo/cache'],'Attire_cache'],
            'Globals' => ['globals','add', ['foo','foo'], 'Attire_globals']
        ];
    }    

    public function pathsProvider()
    {
        return [
           'append'  => ['_foo/bar/', TRUE],
           'prepend' => ['_foo/bar/', FALSE],
        ];
    }

    public function cacheProvider()
    {
        return [
            ['tests/_foo/', FALSE],
            ['tests/_data/', TRUE],
        ];
    }    
}