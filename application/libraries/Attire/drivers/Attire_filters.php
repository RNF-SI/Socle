<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\Extension;
use Attire\Interfaces\Filters;

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
 * Attire Filters
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_filters extends Extension implements Filters
{
	/**
	 * Add default filters (config file)
	 * 
	 * @param array $filters Set of filters 
	 */
	public function init(array $filters)
	{
		foreach ($filters as $key => $value) 
		{
			$this->add($key, $value);
		}
	}

	/**
	 * Add a new filter
	 * 
	 * @param string   $name  Filter Name
	 * @param callable $value Callback filter
	 */
	public function add($name, $filter)
	{
		$this->_parent->environment->addFilter(new \Twig_SimpleFilter($name, $filter));
		return $this;
	}

	/**
	 * Get a defined filter
	 * 
	 * @param  string $name Filter Name
	 * @return \Twig_SimpleFilter
	 */
	public function get($name)
	{
		return $this->_parent->environment->getFilter($name);
	}
}

/* End of file Attire_filters.php */
/* Location: ./application/libraries/Attire/drivers/Attire_filters.php */