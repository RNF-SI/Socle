<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\Config;

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
 * Attire Config
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_config extends Config
{
	/**
	 * Twig Lexer options
	 * @var array
	 */
	private $lexer = FALSE;

	/**
	 * Twig Globals options
	 * @var array
	 * @todo define some globals like site, page, etc.
	 */
	private $globals = [];

	/**
	 * Twig Functions options
	 * @var array
	 */
	private $functions = [];

	/**
	 * Twig filters options
	 * @var array
	 */
	private $filters = [];

	/**
	 * CI views options
	 * @var array
	 */
	private $views = [
		'paths'          => VIEWPATH,
		'file_extension' => '.twig'
	];

	/**
	 * Twig loader options
	 * @var array
	 */
	private $loader = [
		'type'  => 'filesystem',
		'paths' => VIEWPATH
	];

	/**
	 * Sprockets-PHP assets options
	 * @var array
	 */
	private $assets = [
		'manifest_paths' => [],
		'external_paths' => [],
		'prefixes' => [
			'js'   => 'javascripts',
			'css'  => 'stylesheets',
			'img'  => 'images',
			'font' => 'fonts'
		],
		'cache' => TRUE
	];

	/**
	 * Theme options
	 * @var array
	 */
	private $theme = [
		'name'           => NULL,
		'template'       => 'master',
		'layout'         => NULL,
		'path'           => APPPATH . 'themes/',
		'external_paths' => [],
		'file_extension' => '.twig'
	];

	/**
	 * Twig environment options
	 * @var array
	 */
	private $environment = [
		'charset'             => 'UTF-8',
		'base_template_class' => 'Twig_Template',
		'cache'               => FALSE,
		'auto_reload'         => FALSE,
		'strict_variables'    => FALSE,
		'autoescape'          => 'html',
		'debug'               => FALSE
	];

	/**
	 * Set drivers default options
	 *
	 * @param array $options Config file options
	 */
	public function set(array $options)
	{
		foreach ($options as $key => $value)
		{
			switch ($key = strtolower($key))
			{
				case 'environment':
				case 'theme':
				case 'assets':
				case 'loader':
				case 'globals':
				case 'functions':
				case 'filters':
				case 'views':
					$this->{$key} = array_merge($this->{$key}, $value);
					break;
				case 'lexer':
					$this->{$key} = (array) $value;
					break;
			}
		}
	}

	/**
	 * Get driver default options
	 *
	 * @param  string $type Driver name
	 * @return array        Driver options
	 */
	public function get($name)
	{
		return $this->{$name};
	}
}

/* End of file Attire_config.php */
/* Location: ./application/libraries/Attire/drivers/Attire_config.php */
