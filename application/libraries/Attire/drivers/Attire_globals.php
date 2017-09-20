<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\Extension;
use Attire\Interfaces\Globals;

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
 * Attire Globals
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_globals extends Extension implements Globals
{
	/**
	 * Add default globals (config file)
	 * 
	 * @param  array  $globals Set of globals
	 */
	public function init(array $globals)
	{
		foreach ($globals as $key => $value) 
		{
			$this->add($key, $value);
		}
	}	

	/**
	 * Add a new global
	 * 
	 * @param string $name  Global name
	 * @param mixed  $value Global value
	 */
	public function add($name, $value = NULL)
	{
		$this->_parent->environment->addGlobal($name, $value);
		return $this;
	}

	/**
	 * Get all defined globals
	 * 
	 * @return array An array of globals
	 */
	public function get()
	{
		return $this->_parent->environment->getGlobals();
	}

	/**
	 * Merges a context with the defined globals
	 * 
	 * @param  array  $context An array representing the context
	 * @return array           The context merged with the globals
	 */
	public function merge(array $context)
	{
		return $this->_parent->environment->mergeGlobals($context);
	}
}

/* End of file Attire_globals.php */
/* Location: ./application/libraries/Attire/drivers/Attire_globals.php */