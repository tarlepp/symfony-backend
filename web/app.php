<?php
declare(strict_types = 1);
/**
 * Main application file to handle all requests in production mode.
 *
 * @category    App
 * @package     App
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__ . '/../app/autoload.php';

include_once __DIR__ . '/../var/bootstrap.php.cache';

// Register error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

/**
 * Enable APC for autoloading to improve performance.
 * You should change the ApcClassLoader first argument to a unique prefix
 * in order to prevent cache key conflicts with other applications
 * also using APC.
 */
/*
$apcLoader = new Symfony\Component\ClassLoader\ApcClassLoader(sha1(__FILE__), $loader);
$loader->unregister();
$apcLoader->register(true);
*/

// Create new AppKernel
$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

/**
 * When using the HttpCache, you need to call the method in your front controller
 * instead of relying on the configuration parameter
 */
//Request::enableHttpMethodParameterOverride();

// Create new request
$request = Request::createFromGlobals();

// Create response and send it back
$response = $kernel->handle($request);
$response->send();

// Terminate current request/response cycle
$kernel->terminate($request, $response);
