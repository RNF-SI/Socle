<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Attire\Drivers\Extension;
use Attire\Interfaces\Lexer;

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
 * Attire Lexer
 *
 * @package    CodeIgniter
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
class Attire_lexer extends Extension implements Lexer
{
	/**
	 * Lexer stored
	 * @var array
	 */
	protected $_lexer;

	/**
	 * Add default lexer (config file)
	 * 
	 * @param  array $lexer Twig Lexer config options
	 */
	public function init($lexer)
	{
		is_array($lexer) && $this->set($lexer);
	}

	/**
	 * Set lexer
	 * 
	 * @param array $lexer Twig lexer config options
	 */
	public function set(array $lexer)
	{
		$this->_lexer = $lexer;
		return $this;
	}

	/**
	 * Get the current lexer
	 * 
	 * @return \Twig_LexerInterface
	 */
	public function get()
	{
		return $this->_parent->environment->getLexer();
	}	

	/**
	 * Activate the current lexer if exist
	 */
	public function activate()
	{
		if (! empty($this->_lexer)) 
		{
			$core =& $this->_parent->environment->getCore();
			$core->setLexer(new \Twig_Lexer($core, $this->_lexer));
		}
		return $this;
	}
}

/* End of file Attire_lexer.php */
/* Location: ./application/libraries/Attire/drivers/Attire_lexer.php */