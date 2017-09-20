<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
 * CodeIgniter Attire
 *
 * Templating with this class is done by layering the standard CI view system and extending
 * it with Sprockets-PHP (pipeline asset management). The basic idea is that for every single
 * CI view there are individual CSS, Javascript and View files that correlate to it and
 * this structure is conected with the Twig Engine.
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire extends \CI_Driver_Library
{
	/**
	 * @var \CI_Controller
	 */
	public $CI;

	/**
	 * Library name
	 * @var string
	 */
	protected $lib_name = 'Attire';

	/**
	 * Library valid drivers
	 * @var array
	 */
	protected $valid_drivers = [
		'config','loader','environment','views','theme','assets','globals','functions','filters','lexer','cache'
	];

	/**
	 * Class constructor
	 *
	 * @param array $config library params
	 */
	public function __construct(array $options = [])
	{
		// Load CI Instance
    		$this->CI =& get_instance();
	   	try
	   	{
			// Configure all the drivers default options
			$this->config->set($options);
			// Initialize the drivers
			$config = $this->config->get('loader');
			$loader      =& $this->loader->init($config['type'], $config['paths']);
		    	$environment =& $this->environment->init($loader, $this->config->get('environment'));
		    	// Initialize the intern drivers
		    	$config = $this->config->get('views');
		    	$this->views->init(
		    		$loader, 
		    		$config['paths'], 
		    		$config['file_extension']
		    	);
		    	$config = $this->config->get('theme');
		    	$this->theme->init(
		    		$config['name'], 
		    		$config['template'], 
		    		$config['layout'], 
		    		$config['path'], 
		    		$config['external_paths'],
		    		$config['file_extension']
		    	);
		    	$config = $this->config->get('assets');
		    	$this->assets->init(
		    		$config['cache'], 
		    		$config['manifest_paths'],
		    		$config['external_paths'], 
		    		$config['prefixes']
		    	);
		    	// Initialize the component drivers
		    	foreach (['globals','functions','filters','lexer','cache'] as $component)
		    	{
		    		$params = $this->config->get($component);
		    		$this->{$component}->init($params);
		    	}
	   	}
	   	catch (Exception $e)
	   	{
	   		$this->_showError($e);
	   	}
	}

	/**
	 * Render a template
	 *
	 * @param  mixed   $views   A view or an array of views with parameters passed to the template
	 * @param  boolean $return  Output flag
	 * @return string           The output as string if the return flag is set to TRUE
	 */
	public function render($views = NULL, $return = FALSE)
	{
		$this->CI->benchmark->mark('Attire Render Time_start');
		try
		{
			$this->assets->_setPipeline($this->theme);
			$this->lexer->activate();

			$loader  =& $this->loader->getCore();
			$twig    =& $this->environment->getCore();

			foreach ((array) $views as $key => $value) 
			{
				is_string($key) 
					&& $this->views->add($key, $value) 
					|| $this->views->add($value);
			}

			if ($loader instanceof Twig_Loader_Filesystem)
			{
				$loader->prependPath($this->theme->getPath(), $this->theme->getNamespace());

				$template = $twig->loadTemplate('@theme/'.$this->theme->getTemplate());
				$output   = $template->render(['views' => $this->views->get()]);
			}
		}
		catch (Exception $e)
		{
			$this->_showError($e);
		}
		$this->CI->benchmark->mark('Attire Render Time_end');

		if ($return != FALSE)
		{
			return $output;
		}
		#  Manually set the final output string to CI
		return $this->CI->output->set_output($output);
	}

	/**
	 * Show the possible exceptions in CI
	 *
	 * @param  \Exception $e
	 */
	private function _showError(\Exception $e)
	{
		if (is_cli()) { throw $e; }
		list($trace) = $e->getTrace();
		$message     = 'Exception: '.$trace['class'].' with the message: '.$e->getMessage();
		return show_error($message, 500, 'Attire error');
	}
}

/* End of file Attire.php */
/* Location: ./application/libraries/attire/Attire.php */
