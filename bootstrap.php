<?php

namespace Nerd;

/**
 * Do not edit this file. If you need to do any framework bootstrapping you should
 * do it in the bootstrap.php file that lives within the application directory.
 */

// Aliasing rules
use Nerd\Autoloader;

// Definitions
!defined('DS') and define('DS', DIRECTORY_SEPARATOR);

define('Nerd\LIBRARY_PATH', dirname(__DIR__));
define('Nerd\VENDOR_PATH', dirname(\Nerd\LIBRARY_PATH).'/vendor');
define('Nerd\DOCROOT', dirname(\Nerd\LIBRARY_PATH).'/public');

/**
 * Get and register the Nerd autoloader.
 */
include \Nerd\LIBRARY_PATH.'/nerd/classes/autoloader.php';
Autoloader::register();

/**
 * Test for CLI, load either application or Geek bootstrap.
 */
if (PHP_SAPI === 'cli') {
    include \Nerd\LIBRARY_PATH.DS.'geek/bootstrap.php';
} else {
    include \Nerd\LIBRARY_PATH.DS.'application/bootstrap.php';
}
