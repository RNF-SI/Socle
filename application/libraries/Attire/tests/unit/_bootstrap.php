<?php

/**
 * Part of Attire Library
 *
 * @author     David Sosa Valdes <https://github.com/davidsosavaldes>
 * @license    MIT License
 * @copyright  2016 David Sosa Valdes
 * @link       https://github.com/davidsosavaldes/Attire
 *
 * Based on https://raw.githubusercontent.com/kenjis/codeigniter-ss-twig/master/ci_instance.php
 * Thanks Kenji!
 */

define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'testing');

date_default_timezone_set('UTC');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;

	case 'testing':
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		}
		else
		{
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
		}
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

$system_path        = 'vendor/codeigniter/framework/system';
$application_folder = 'vendor/codeigniter/framework/application';
$composer_autoload  = 'vendor/autoload.php';
$test_path          = 'tests/_application';

if (! is_dir($test_path)) 
{
	print("Your test folder {$test_path} path does not appear to be set correctly.\n");
	exit(3);
}

define('TESTPATH', rtrim($test_path,'/').'/');

if (($_temp = realpath($system_path)) !== FALSE)
{
	$system_path = $_temp.'/';
}
else
{
	$system_path = rtrim($system_path, '/').'/';
}

if (! is_dir($system_path))
{
	print("Your system folder path does not appear to be set correctly.\n");
	exit(3);
}

define('BASEPATH', str_replace('\\', '/', $system_path));

if (is_dir($application_folder))
{
	if (($_temp = realpath($application_folder)) !== FALSE)
	{
		$application_folder = $_temp;
	}
	define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
}
else
{
	if ( ! is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
	{
		print('Your application folder path does not appear to be set correctly.' 
		.' Please open the following file and correct this: '.SELF);
		exit(3);
	}
	define('APPPATH', BASEPATH.$application_folder.DIRECTORY_SEPARATOR);
}

if ($fpath = realpath(rtrim($application_folder,'/').'/../')) 
{
	// Path to the front controller (this file) directory
	define('FCPATH', $fpath.DIRECTORY_SEPARATOR);
}

define('VIEWPATH', APPPATH.'views'.DIRECTORY_SEPARATOR);

/*
| -------------------------------------------------------------------
|  CodeIgniter Instance
| -------------------------------------------------------------------
*/
require_once(BASEPATH.'core/Common.php');

if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/constants.php')) {
    require(APPPATH . 'config/' . ENVIRONMENT . '/constants.php');
} else {
    require(APPPATH . 'config/constants.php');
}

# Make sure some config variables are set correctly
// get_config([

// ]);

if (! file_exists($composer_autoload)) 
{
	print('Your composer autoload file path does not appear to be set correctly.');
	exit(3);
}
else
{
	require_once($composer_autoload);
}

$charset = strtoupper(config_item('charset'));
ini_set('default_charset', $charset);

if (extension_loaded('mbstring')) {
    define('MB_ENABLED', TRUE);
    // mbstring.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('mbstring.internal_encoding', $charset);
    // This is required for mb_convert_encoding() to strip invalid characters.
    // That's utilized by CI_Utf8, but it's also done for consistency with iconv.
    mb_substitute_character('none');
} else {
    define('MB_ENABLED', FALSE);
}

// There's an ICONV_IMPL constant, but the PHP manual says that using
// iconv's predefined constants is "strongly discouraged".
if (extension_loaded('iconv')) {
    define('ICONV_ENABLED', TRUE);
    // iconv.internal_encoding is deprecated starting with PHP 5.6
    // and it's usage triggers E_DEPRECATED messages.
    @ini_set('iconv.internal_encoding', $charset);
} else {
    define('ICONV_ENABLED', FALSE);
}

$BM   =& load_class('Benchmark', 'core');
$CFG  =& load_class('Config', 'core');
$UNI  =& load_class('Utf8', 'core');
$SEC  =& load_class('Security', 'core');
//$RTR =& load_class('Router', 'core');
$IN   =& load_class('Input', 'core');
$LANG =& load_class('Lang', 'core');
$OUT  =& load_class('Output','core');

require_once BASEPATH.'core/Controller.php';

function &get_instance() 
{
    return CI_Controller::get_instance();
}

$CI = new CI_Controller();
$CI->load->add_package_path(TESTPATH);
$CI->load->driver('attire');
