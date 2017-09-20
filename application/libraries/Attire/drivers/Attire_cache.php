<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\Extension;
use Attire\Interfaces\Cache;

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
 * Attire Cache
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_cache extends Extension implements Cache
{
	/**
	 * Init method (prototype)
	 */
	public function init(){}

	/**
	 * Set Twig environment cache
	 * 
	 * @param string $path Cache directory path
	 */
	public function set($path)
	{
		$this->_parent->environment->setCache($path);
		return $this;
	}

	/**
	 * Get Twig environment cache
	 * 
	 * @return string Cache path
	 */
	public function get()
	{
		return $this->_parent->environment->getCache();
	}

	/**
	 * Get Twig environment cache filename (deprecated method in Twig 2.0)
	 * 
	 * @param  string $name Template name
	 * @return string       Cache filename and path
	 */
	public function getFilename($name)
	{
		return FALSE; #$this->_parent->environment->getCacheFilename($name);
	}
}

/* End of file Attire_cache.php */
/* Location: ./application/libraries/Attire/drivers/Attire_cache.php */