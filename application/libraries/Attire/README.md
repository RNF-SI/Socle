**Documentation for Attire is available at [http://davidsosavaldes.github.io/Attire/](http://davidsosavaldes.github.io/Attire/).**

#Attire [![Coverage Status](https://coveralls.io/repos/github/davidsosavaldes/Attire/badge.svg?branch=3.0-stable)](https://coveralls.io/github/davidsosavaldes/Attire?branch=3.0-stable) [![Latest Stable Version](https://poser.pugx.org/dsv/attire/v/stable)](https://packagist.org/packages/dsv/attire) [![Total Downloads](https://poser.pugx.org/dsv/attire/downloads)](https://packagist.org/packages/dsv/attire) [![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/davidsosavaldes/Attire/develop/LICENSE)

Attire Driver supports template inheritance using **Twig** template engine and **Sprockets-PHP** asset management framework in CodeIgniter 3.
 
## Tests

To run the tests you need to first clone the repository and install the dependencies. You do this via composer with the following command:

	php composer --dev install

Once that is done you need to create an application test environment using symlinks:

	mkdir -p tests/_application/libraries/
	ln -s ~/full/path/to/Attire tests/_application/libraries/Attire
	ln -s ~	full/path/to/Attire/dist/config tests/_application/config

And finnally in `drivers/Attire_theme.php` driver class we need to change the default theme path:
	
	# From:
	private $_path = APPPATH.'libraries/Attire/dist/';
	# To:
	private $_path = TESTPATH.'libraries/Attire/dist/';

Also check if the directory paths used in the `tests/unit/_bootstrap.php` file are correct:

	$system_path        = 'vendor/codeigniter/framework/system';
	$application_folder = 'vendor/codeigniter/framework/application';
	$composer_autoload  = 'vendor/autoload.php';
	$test_path          = 'tests/_application';

And finally run the tests with codeception

	php vendor/bin/codecept run	

### Other Twig Implementations for Codeigniter

* [https://github.com/kenjis/codeigniter-ss-twig](https://github.com/kenjis/codeigniter-ss-twig)
