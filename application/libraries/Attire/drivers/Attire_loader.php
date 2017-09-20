<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\Base;
use Attire\Interfaces\Loader;

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
 * Attire Loader
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_loader extends Base implements Loader
{
	/**
	 * Create/Set a Twig Loader instance
	 *
	 * @param  string $type   Loader type
	 * @param  array  $params Loader params
	 * @return \Twig_LoaderInterface
	 */
	public function &init($type, $params)
	{
		/**
		 * @todo String Loader is deprecated, make something
		 */
		switch (strtolower($type))
		{
			case 'array':
				$this->setCore(new \Twig_Loader_Array($params));
				break;
			case 'chain':
				$loaders = [];
				foreach ($params as $key => $value)
				{
					$loaders[] = new \Twig_Loader_Array($value);
				}
				$this->setCore(new \Twig_Loader_Chain($loaders));
				break;
			default:
				$this->setCore(new \Twig_Loader_Filesystem($params));
				break;
		}
		return $this->getCore();
	}
}

/* End of file Attire_environment.php */
/* Location: ./application/libraries/Attire/drivers/Attire_environment.php */
