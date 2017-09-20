<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\File;
use Attire\Exceptions\ThemeException;
use Attire\Interfaces\Theme;

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
 * Attire Theme
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_theme extends File implements Theme
{
	/**
	 * Theme directory used as name
	 * @var string
	 */
	private $_name = 'template';

	/**
	 * Master template rendered
	 * @var string
	 */
	private $_template = 'master';

	/**
	 * Slave layout if exists
	 * @var string
	 */
	private $_layout = 'layouts/default';

	/**
	 * Themes default path
	 * @var string
	 */
	private $_path = APPPATH.'libraries/Attire/dist/';

	/**
	 * Identifier of the main namespace.
	 */
	const MAIN_NAMESPACE = 'theme';

	/**
	 * Add default theme options (config file)
	 *
	 * @param  string $name     		Theme name.
	 * @param  string $template 		Master template.
	 * @param  string $layout   		Slave layout.
	 * @param  string $path     		Default path as a string.
	 * @param  array  $external_paths 	Paths where will find the files used to 
	 *                                 	extend a template from another one.
	 * @param  string $ext      		File extension.
	 */
	public function init($name, $template, $layout, $path, array $external_paths, $ext)
	{
		// Add the default path
		$this->_parent->loader->addPath($this->getPath(), self::MAIN_NAMESPACE);
		// Set defaults
		$this->setName($name);
		$this->setTemplate($template);
		$this->setLayout($layout);
		$this->setPath($path);
		$this->setExternalPaths($external_paths);
		$this->setExt($ext);		
	}

	/**
	 * Get main namespace identifier.
	 * 
	 * @return string
	 */
	public function getNamespace()
	{
		return self::MAIN_NAMESPACE;
	}

	/**
	 * Add a path used to store the extend template files
	 * 
	 * @param string $path External path
	 */
	public function addExternalPath($path)
	{
		$this->_parent->loader->prependPath($path, self::MAIN_NAMESPACE);
		return $this;
	}

	/**
	 * Set the external paths used to store the extend template files
	 * 
	 * @param array $external_paths Paths where will find the files used to extend a template from another one
	 */
	public function setExternalPaths(array $external_paths)
	{
		foreach ($external_paths as $path) 
		{
			$this->_parent->loader->addPath($path, self::MAIN_NAMESPACE);		
		}
		return $this;
	}

	/**
	 * Get the theme name
	 *
	 * @return string Theme name
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * Set a theme name
	 *
	 * @param string $name Theme name
	 */
	public function setName($name)
	{
		($name !== NULL) && $this->_name = $name;
		return $this;
	}

	/**
	 * Set theme default path (without name)
	 *
	 * @param string $path Theme path
	 */
	public function setPath($path)
	{
		realpath($path.$this->_name) && $this->_path = realpath($path).'/';
		return $this;
	}

	/**
	 * Get theme default path
	 *
	 * @return string Theme path
	 */
	public function getPath()
	{
		if (! is_dir($path = $this->_path.$this->_name))
		{
			throw new ThemeException('The directory "'.$path.'" does not exist.');
		}
		return rtrim($path,'/').'/';
	}

	/**
	 * Set a new layout
	 *
	 * @param string $layout    Layout filename path
	 * @param string $directory Directory where the layout is stored (relative to theme path)
	 */
	public function setLayout($layout, $directory = 'layouts/')
	{
		if (is_string($layout))
		{
			if (strpos($layout, '/') !== FALSE)
			{
				list($directory, $layout) = explode('/', $layout);
			}
			(! $this->haveExt($layout)) && $layout .= $this->getExt();
			$this->_layout = rtrim($directory,'/').'/'.$layout;
		}
		return $this;
	}

	/**
	 * Get the current layout
	 *
	 * @return mixed Return the actual path if exist else FALSE
	 */
	public function getLayout()
	{
		if ($this->_layout !== NULL) 
		{
			(! $this->haveExt($this->_layout)) && $this->_layout .= $this->getExt();			
			return $this->_layout;
		}
		return FALSE;
	}

	/**
	 * Set the master template
	 *
	 * @param string $template Template name, ignore if not string
	 */
	public function setTemplate($template)
	{
		is_string($template) && $this->_template = $template;
		return $this;
	}

	/**
	 * Get the current template
	 *
	 * @return string Return the actual path if exist else FALSE
	 */
	public function getTemplate()
	{
		(! $this->haveExt($this->_template)) && $this->_template .= $this->getExt();
		$template = ($layout = $this->getLayout())? $layout : $this->_template;
		return ($template !== NULL)? $template : FALSE;
	}
}

/* End of file Attire_theme.php */
/* Location: ./application/libraries/Attire/drivers/Attire_theme.php */
