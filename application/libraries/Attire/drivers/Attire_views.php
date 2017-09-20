<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\File;
use Attire\Interfaces\Views;

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
 * Attire Views
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_views extends File implements Views
{
	/**
	 * @var \Twig_LoaderInterface
	 */
	private $_loader;

	/**
	 * Set of views stored
	 * @var array
	 */
	private $_views = [];

	/**
	 * \Twig_LoaderInterface::MAIN NAMESPACE
	 */
	const MAIN_NAMESPACE = '__main__';

	/**
	 * Add default view options (config file)
	 *
	 * @param  \Twig_LoaderInterface $loader
	 * @param  mixed                 $paths  Path(s) where the views are stored
	 * @param  string                $ext    File extension
	 */
	public function init(\Twig_LoaderInterface $loader, $paths = VIEWPATH, $ext = '.twig')
	{
		$this->_loader =& $loader;
		$this->setPaths($paths);
		$this->setExt($ext);
	}

	/**
	 * Get the Twig Loader path(s)
	 *
	 * @return array Paths where Attire looks for views
	 */
	public function getPaths()
	{
		return $this->_loader->getPaths();
	}

	/**
	 * Add a path to Twig Loader
	 *
	 * @param string $path      Path where to look for templates
	 * @param string $namespace Path namespace
	 */
	public function addPath($path, $namespace = self::MAIN_NAMESPACE)
	{
		$this->_loader->addPath($path, $namespace);
		return $this;
	}

	/**
	 * Prepend a path to Twig Loader
	 *
	 * @param string $path      Path where to look for templates
	 * @param string $namespace Path namespace
	 */
	public function prependPath($path, $namespace = self::MAIN_NAMESPACE)
	{
		$this->_loader->prependPath($path, $namespace);
		return $this;
	}

	/**
	 * Sets the paths where templates are stored
	 *
	 * @param mixed  $paths     Path or an array of paths where to look for templates
	 * @param string $namespace Path namespace
	 */
	public function setPaths($paths, $namespace = self::MAIN_NAMESPACE)
	{
		$this->_loader->setPaths($paths, $namespace);
		return $this;
	}

	/**
	 * Add a view
	 *
	 * @param string $view   View filename
	 * @param array  $params View params
	 */
	public function add($view, array $params = [])
	{
		(! $this->haveExt($view)) && $view.= $this->getExt();
		$this->_views[$view] = $params;
		return $this;
	}

	/**
	 * Get stored views
	 *
	 * @return array Set of stored views with their respective params
	 */
	public function get()
	{
		return $this->_views;
	}

	/**
	 * Remove specific view
	 *
	 * @param string $view View name
	 */
	public function remove($view)
	{
		(! $this->haveExt($view)) && $view.= $this->getExt();
		unset($this->_views[$view]);
		return $this;
	}

	/**
	 * Clear all the stored views
	 */
	public function reset()
	{
		$this->_views = [];
		return $this;
	}
}

/* End of file Attire_view.php */
/* Location: ./application/libraries/Attire/drivers/Attire_view.php */
