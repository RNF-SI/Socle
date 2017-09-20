<?php
namespace Attire\Drivers;

/**
 * Attire File Class
 *
 * @package    Attire
 * @subpackage Drivers
 * @category   Driver
 * @author     David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 */
abstract class File extends \CI_Driver
{
	use \Attire\Traits\Ancestor;
	
	/**
	 * File extension
	 * @var string
	 */
	private $_ext = '.twig';

	/**
	 * Check if file have extension
	 * 
	 * @param  string  $view Filename
	 * @return boolean       TRUE if the file have extension defined and is valid else FALSE
	 */
	public function haveExt($view)
	{
		$info = new \SplFileInfo($view);
		$ext = $info->getExtension();
		return (! empty($ext)); #&& $this->isValidExt($ext);		
	}		

	/**
	 * Check if the file have a valid extension
	 * 
	 * @param  string  $ext File extension
	 * @return boolean      TRUE if is valid
	 */
	public function isValidExt($ext)
	{
		return preg_match('/^.*\.(twig|php|php.twig|html|html.twig)$/i', $ext);	
	}

	/**
	 * Set a new file extension
	 * 
	 * @param string $ext Set it if is valid
	 */
	public function setExt($ext)
	{
		return $this->isValidExt($ext) && $this->_ext = $ext;
	}

	/**
	 * Get current file extension
	 * 
	 * @return string File extension
	 */
	public function getExt()
	{
		return $this->_ext;
	}
}

/* End of file File_Component.php */
/* Location: ./application/libraries/Attire/src/File_Component.php */