<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Loader
|--------------------------------------------------------------------------
|
| Loaders are responsible for loading templates from a resource such as the file system
|
| Here is a list of the built-in loaders:
|
| * Filesystem (default)
| * Array
| * Chain
|
*/
$config['loader'] = [
	'type'  => 'filesystem',
	'paths' => NULL
];

/*
|--------------------------------------------------------------------------
| Environment
|--------------------------------------------------------------------------
|
| The following options are available:
|
| * charset             : The charset used by the templates.
| * base_template_class : The base template class to use for generated templates.
| * cache               : An absolute path where to store the compiled templates, or false to disable caching.
| * auto_reload         : When developing with Twig, it's useful to recompile the template whenever the source code changes.
| * strict_variables    : If set to false, Twig will silently ignore invalid variables and replace them with a null value.
| * autoescape          : HTML auto-escaping will be enabled by default for all templates .
| * debug               : Enable debug mode.
|
*/
$config['environment'] = [
	'charset'             => 'UTF-8',
	'base_template_class' => 'Twig_Template',
	'cache'               => FALSE,
	'auto_reload'         => FALSE,
	'strict_variables'    => FALSE,
	'autoescape'          => 'html',
	'debug'               => FALSE
];

/*
|--------------------------------------------------------------------------
| Lexer
|--------------------------------------------------------------------------
|
| Check the twig syntax settings:
| 	http://twig.sensiolabs.org/doc/recipes.html#customizing-the-syntax
|
| $lexer = [
|    'tag_comment'   => array('{#', '#}'),
|    'tag_block'     => array('{%', '%}'),
|    'tag_variable'  => array('{{', '}}'),
|    'interpolation' => array('#{', '}'),
| ];
*/
$config['lexer'] = FALSE;

/*
|--------------------------------------------------------------------------
| Theme
|--------------------------------------------------------------------------
|
| Here you may specify the directory path to your attire themes folder. 
| Typically, it will be within your application path.
|
*/
$config['theme'] = [
	'name'           => NULL,
	'template'       => 'master',
	'layout'         => NULL,
	'path'           => APPPATH.'themes/',
	'external_paths' => [],
	'file_extension' => '.twig'
];

/*
|--------------------------------------------------------------------------
| Views
|--------------------------------------------------------------------------
|
| Path to your views directories.
|
*/
$config['views'] = [
	'paths'          => [ VIEWPATH ],
	'file_extension' => '.twig'
];

/*
|--------------------------------------------------------------------------
| Assets
|--------------------------------------------------------------------------
|
| Path to your assets folders. 
| 
| Example:
|	external_paths => ['bower_components/', ...]
|
| Typical scenarios: 
|	- outside your application directory 
|	- inside your public directory.
|
| Cache path: FCPATH.'assets/' # Sprockets-PHP create the 'assets' directory by default
|
*/
$config['assets'] = [
	'manifest_paths' => [],
	'external_paths' => [],
	'prefixes' => [
		'js'   => 'javascripts',
		'css'  => 'stylesheets',
		'img'  => 'images',
		'font' => 'fonts'
	],
	'cache' => TRUE
];

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| Allows to add Codeigniter functionality in Twig Environment that come 
| from other libraries or helpers. 
| 
| Example:
|
| 	$config['functions'] = array(
|		'base_url' => function($path = ""){ 
|			return base_url($path); 
|		},
| 	);
| 
| Call the functions in Twig environment:
|		
|	{{ base_url('foo_fighters') }}
| 
| Remember to load/autoload the library or helper bafore the render method.
|
*/
$config['functions'] = [];

/*
|--------------------------------------------------------------------------
| Global Variables
|--------------------------------------------------------------------------
|
| Global variables can be registered in the Twig environment. Same as 
| declare a function:
|
| $config['global_vars'] = array(
| 	'some' => 'hello world',
| );
|
| Call the functions in the template:
|		
|	{{ some }}
|
*/
$config['globals'] = [];


/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
|
| Variables can be modified by filters. Filters are separated from the 
| variable by a pipe symbol (|) and may have optional arguments in parentheses. 
|
| Multiple filters can be chained. The output of one filter is applied to the next. 
| 
| Example:
|
| 	$config['filters'] = array(
|		'base_url' => function($path = ""){ 
|			return base_url($path); 
|		},
| 	);
| 
| Call the functions in the template:
|		
|	{{ 'foo_fighters' | base_url }}
|
*/
$config['filters'] = [];
