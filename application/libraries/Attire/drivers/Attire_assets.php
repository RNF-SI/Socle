<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\Extension;
use Attire\Interfaces\Assets;
use Sprockets\Pipeline;
use Sprockets\Cache;

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * @package   CodeIgniter
 * @author    EllisLab Dev Team
 * @copyright Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license   http://opensource.org/licenses/MIT	MIT License
 * @link      http://codeigniter.com
 * @since     Version 1.0.0
 */

/**
 * Attire Assets
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_assets extends Extension implements Assets
{
	/**
	 * Pipeline arguments 
	 * @var array
	 */
	private $_options = [];

	/**
	 * Pipeline paths where store the manifests
	 * @var array
	 */
	private $_manifest_paths = [];

	/**
	 * Pipeline external paths
	 * @var array
	 */
	private $_external_paths = [];

	/**
	 * Pipeline prefixes
	 * @var array
	 */
	private $_prefix = [];

	/**
	 * Manifest filename
	 * @var string
	 */
	private $_manifest;

	/**
	 * Pipeline Cache option key
	 */
	const CACHE_TAG = 'CACHE_DIRECTORY';

	/**
	 * Pipeline manifests default filename
	 */
	const MANIFEST_NAME = 'application';

	/**
	 * Initialize the class
	 * 
	 * @param  mixed  $path           Set the cache path directory
	 * @param  array  $external_paths Set the extrnal paths 
	 * @param  array  $prefixes       Set the default prefixes (css, js, fonts, images)
	 */
	public function init($path, array $manifests_paths, array $external_paths, array $prefixes)
	{	
		$this->setCache($path);
		$this->setPaths($external_paths);
		$this->setPrefixes($prefixes);
		$this->setManifestPaths($manifests_paths);
		$this->_manifest = self::MANIFEST_NAME;
	}

	/**
	 * Set manifest filename used in the template
	 * 
	 * @param string $name Manifest file name
	 */
	public function setManifest($name)
	{
		($name !== NULL) && $this->_manifest = preg_replace('/\\.[^.\\s]{3,4}$/', '', $name);
		return $this;
	}

	/**
	 * Get the manifest filename
	 * 
	 * @return string
	 */
	public function getManifest()
	{
		return $this->_manifest;
	}


	/**
	 * Add directory path
	 * 
	 * @param string $path Directory path
	 */
	public function addPath($path)
	{
		array_push($this->_external_paths, rtrim($path,'/').'/');
		return $this;
	}	

	/**
	 * Get paths
	 * 
	 * @return array The pipeline external paths
	 */
	public function getPaths()
	{
		return $this->_external_paths;	
	}

	/**
	 * Add manifest path
	 * 
	 * @param string $path Directory path
	 */
	public function addManifestPath($path)
	{
		array_push($this->_manifest_paths, rtrim($path,'/').'/');
		return $this;		
	}

	/**
	 * Get manifest paths
	 * 
	 * @return array The Pipeline template directories paths
	 */
	public function getManifestPaths()
	{
		return $this->_manifest_paths;
	}

	/**
	 * Set default manifest paths
	 * 
	 * @param array $paths [description]
	 */
	public function setManifestPaths(array $paths)
	{
		$this->_manifest_paths = array_merge([
				'%theme%/assets/',
				APPPATH.'themes/_shared/assets/'
			],
			array_map(function($path){
				return rtrim($path,'/').'/';
			}, $paths)
		);
		return $this;
	}	

	/**
	 * Set default external paths
	 * 
	 * @param array $paths [description]
	 */
	public function setPaths(array $paths)
	{
		$this->_external_paths = array_map(function($path){
			return rtrim($path,'/').'/';
		}, $paths);
		return $this;
	}	

	/**
	 * Prepend directory path
	 * 
	 * @param  string $path Directory path
	 */
	public function prependPath($path)
	{
		array_unshift($this->_external_paths, rtrim($path,'/').'/');
		return $this;
	}

	/**
	 * Get prefixes
	 * 
	 * @return array Set of prefixes (css, js, fonts and images)
	 */
	public function getPrefixes()
	{
		return $this->_prefix;
	}

	/**
	 * Get specific prefix
	 * 
	 * @param  string $type Prefix type
	 * @return mixed        Returns string value if prefix is assigned else FALSE
	 */
	public function getPrefix($type)
	{
		return isset($this->_prefix[$type])
			? $this->_prefix[$type]
			: FALSE;
	}

	/**
	 * Set default prefixes
	 * 
	 * @param array $prefix Set of prefixes
	 */
	public function setPrefixes(array $prefix)
	{
		$this->_prefix = array_merge([
			'js'   => 'javascripts',
			'css'  => 'stylesheets',
			'img'  => 'images',
			'font' => 'fonts'
		], $prefix);
		return $this;
	}

	/**
	 * Set prefix
	 * 
	 * @param string $type   Prefix type
	 * @param string $prefix New prefix
	 */
	public function setPrefix($type, $prefix)
	{
		$this->_prefix[$type] = $prefix;
		return $this;
	}

	/**
	 * Set default pipeline options
	 */
	private function _setOptions()
	{
		return $this->_options = array_merge([
			'template' => [
				'directories' => $this->_manifest_paths,
				'prefixes' => $this->_prefix
			],
			'external' => [
				'directories' => $this->_external_paths
			]
		], $this->_options);
	}

	/**
	 * Get the actual cache path
	 * 
	 * @param  string $tag Cache directory tag
	 * @return mixed       Return cache path if active else FALSE
	 */
	public function getCache($tag = self::CACHE_TAG)
	{
		return ($this->cacheActive())? $this->_options[$tag] : FALSE;
	}

	/**
	 * Set the cache directory path
	 * 
	 * @param string $directory New directory path
	 * @param string $tag       Cache directory tag
	 */
	public function setCache($directory, $tag = self::CACHE_TAG)
	{
		$this->unsetCache($tag);
		if ($directory === TRUE) 
		{
			$this->_options[$tag] = '';
		}
		elseif ($directory !== FALSE && realpath($directory)) 
		{
			$this->_options[$tag] = rtrim($directory,'/').'/';
		}
		return $this;
	}

	/**
	 * Unset cache
	 * 
	 * @param  string $tag Cache directory tag
	 */
	public function unsetCache($tag = self::CACHE_TAG)
	{
		unset($this->_options[$tag]);
		return $this;
	}

	/**
	 * Check if cache is active
	 * 
	 * @return boolean 
	 */
	public function cacheActive()
	{
		return isset($this->_options[self::CACHE_TAG]);
	}

	/**
	 * Create and set new Pipeline instance
	 * 
	 * @param  Attire_theme $theme
	 * @return Sprockets\Pipeline
	 */
	public function _setPipeline(\Attire_theme $theme)
	{
		($cache_path = $this->getCache()) && $this->addPath($cache_path);
		$theme = $theme->getPath();
		$paths = $this->_setOptions();
		//Replace the pipeline string format with the theme name and path
		array_walk_recursive($paths, function(&$path) use ($theme) {
			$path = str_replace('%theme%', rtrim($theme,'/'), $path);
		});
		$pipeline = new Pipeline($paths);
		$this->_setFunction($pipeline);
		return $pipeline;
	}

	/**
	 * Set Pipeline functions with Twig
	 * @param Pipeline $pipeline
	 */
	private function _setFunction(Pipeline $pipeline)
	{
		if ($this->cacheActive()) 
		{
			$this->_parent->CI->load->helper('url');
			
			$function = function($type, array $vars = [], array $options = []) 
				use ($pipeline){
					// Lets try to extract the manifest and extension of the first param
					@list($_manifest, $_type) = explode('.', $type);
					$options = array_merge([
						'manifest' => ($_type !== NULL)? $_manifest : $this->_manifest,
					], $options);
					return base_url((string) new Cache($pipeline, ($_type !== NULL)? $_type : $type, $vars, $options));
				};
		}
		else
		{
			$function = function($type, $manifest = NULL, $vars = array(), $full = false) 
				use ($pipeline){
					// Lets try to extract the manifest and extension of the first param
					@list($_manifest, $_type) = explode('.', $type);
					($_type !== NULL) && $type = $_type && $manifest = $_manifest;
					return $pipeline($type, ($manifest === NULL)? $this->_manifest : $manifest, $vars, $full);
				};
		}
		$this->_parent->functions->add('sprockets', $function);		
	}
}

/* End of file Attire_environment.php */
/* Location: ./application/libraries/Attire/drivers/Attire_environment.php */