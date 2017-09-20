<?php
namespace Attire\Traits;

use Attire\Exceptions\AncestorException;

/**
 * Attire Ancestor Trait
 *
 * Used by all the extension classes to call the _parent methods and properties.
 *
 * @package    Attire
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
trait Ancestor
{
	/**
	 * Get the parent driver if exist
	 * 
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function __get($name)
	{
		if (! property_exists($this->_parent, $name)) 
		{
			throw new AncestorException("Error Processing Parent Driver Request");
		}
		return $this->_parent->{$name};
	}

	/**
	 * [__call description]
	 * @param  [type] $method [description]
	 * @param  [type] $args   [description]
	 * @return [type]         [description]
	 */
	public function __call($method, $args = NULL)
	{
		return call_user_func_array(array($this->_parent, $method), $args);
	}
}
