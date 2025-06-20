<?php

declare(strict_types=1);

/**
* The directory separator to be used
*/
define('DS', DIRECTORY_SEPARATOR);

/**
* The root directory of the application.
*
* you can place this directory outside of your web directory,
 * for example "/home/your_app", etc.
*/
define('ROOT_PATH', dirname(realpath(__DIR__)) . DS);

/**
* The path to the "app" directory.
*
* That contains most of the application files (Action, Entities, Repositories, etc.)
*/
define('APP_PATH', ROOT_PATH . 'app' . DS);

/**
* The path to the configuration directory.
*
* That contains most of the configuration files for your
* application (database, logging, translation, authentication, etc.)
*/
define('CONFIG_PATH', ROOT_PATH . 'config' . DS);

/**
* The path to the storage directory.
*
* That contains most of the application cache, session, migration, log files
*/
define('STORAGE_PATH', ROOT_PATH . 'storage' . DS);

/**
* The path to the directory of composer dependencies for your application.
*/
define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);

/**
* The environment of your application something 
 * like production, test, development, etc.
*
*/
define('ENVIRONMENT', 'dev');

// Require the composer autoload file
require VENDOR_PATH . 'autoload.php';

use Platine\Framework\App\Application;
use Platine\Framework\Kernel\HttpKernel;

$app = new Application();
$app->setConfigPath(CONFIG_PATH)
      ->setRootPath(ROOT_PATH)
      ->setAppPath(APP_PATH)
      ->setVendorPath(VENDOR_PATH)
      ->setStoragePath(STORAGE_PATH)
      ->setEnvironment(ENVIRONMENT);

/** @var HttpKernel $kernel */
$kernel = $app->make(HttpKernel::class);

// let's go, do the magic
$kernel->run();
