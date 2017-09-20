<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\Base;
use Attire\Interfaces\Environment;

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
 * Attire Environment
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_environment extends Base implements Environment
{
	/**
	 * Create a Twig Environment
	 * 
	 * @return \Twig_environment
	 */
	public function &init(\Twig_LoaderInterface $loader, array $options)
	{	
		$this->setCore(
			new \Twig_Environment($loader, array_merge([
					'charset'             => 'UTF-8',
					'base_template_class' => 'Twig_Template',
					'cache'               => FALSE,
					'auto_reload'         => FALSE,
					'strict_variables'    => FALSE,
					'autoescape'          => 'html',
					'debug'               => FALSE	
				], $options)
			)
		);

		isset($options['debug']) && $options['debug'] !== FALSE 
			&& $this->getCore()->addExtension(new Twig_Extension_Debug());

		return $this->getCore();
	}
}

/* End of file Attire_environment.php */
/* Location: ./application/libraries/Attire/drivers/Attire_environment.php */