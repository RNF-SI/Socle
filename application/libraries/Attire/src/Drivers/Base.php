<?php
namespace Attire\Drivers;

/**
 * Attire Base Class
 *
 * @package    Attire
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
abstract class Base extends \CI_Driver
{
	/**
	 * Class core
	 * @var object
	 */
	private $_core;

	/**
	 * Get the core instance
	 * 
	 * @return [type] [description]
	 */
	public function &getCore()
	{
		return $this->_core;
	}	

	/**
	 * Set a new core class
	 * @param [type] $object [description]
	 * @param [type] $params [description]
	 */
	public function setCore($object)
	{
		return $this->_core = (is_object($object))
			? $object
			: new \stdClass;
	}

	/**
	 * Call a core method if not defined by the child
	 * 
	 * @param  string $method Method name
	 * @param  mixed  $args   Method arguments
	 * @return mixed          Returns the core method or the _parent method output
	 */
  	public function __call($method, $args = NULL)
  	{
  		$core =& $this->getCore();
  		return (method_exists($core, $method))
  			? call_user_func_array(array($core, $method), (array) $args)
  			: parent::__call($method, $args);
  	}	
}

/* End of file Environment.php */
/* Location: ./application/libraries/Attire/src/Drivers/Base.php */
