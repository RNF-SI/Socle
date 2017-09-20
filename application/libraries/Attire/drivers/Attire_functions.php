<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\Extension;
use Attire\Interfaces\Functions;

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
 * Attire Functions
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_functions extends Extension implements Functions
{
	/**
	 * Add default functions (config file)
	 * 
	 * @param  array  $functions Set of functions
	 */
	public function init(array $functions)
	{
		foreach ($functions as $key => $value) 
		{
			$this->add($key, $value);
		}
	}

	/**
	 * Add a new function 
	 * 
	 * @param name 		$name  Function name
	 * @param callable 	$value Callback Function
	 */
	public function add($name, $function = NULL)
	{
		$this->_parent->environment->addFunction(new \Twig_SimpleFunction($name, $function));
		return $this;
	}

	/**
	 * Get a defined function
	 * 
	 * @param  string $name Function name
	 * @return \Twig_SimpleFunction
	 */
	public function get($name)
	{
		return $this->_parent->environment->getFunction($name);
	}
}

/* End of file Attire_functions.php */
/* Location: ./application/libraries/Attire/drivers/Attire_functions.php */