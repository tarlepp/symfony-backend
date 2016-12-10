<?php
declare(strict_types = 1);
/**
 * Main application file to handle all requests in development mode.
 *
 * @category    App
 * @package     App
 * @author      TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

/**
 * If you don't want to setup permissions the proper way, just uncomment the following PHP line
 * read http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup
 * for more information
 *
 * umask(0000);
 */

// Get allowed IP addresses
$allowedAddress = require_once __DIR__ . '/../app/config/development_ip_addresses.php';

/**
 * This check prevents access to debug front controllers that are deployed by accident to production servers.
 * Feel free to remove this, extend it, or make something more sophisticated.
 */
if (!in_array('*', $allowedAddress)
    && (
        isset($_SERVER['HTTP_CLIENT_IP'])
        || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
        || !(in_array(@$_SERVER['REMOTE_ADDR'], $allowedAddress)
        || php_sapi_name() === 'cli-server')
    )
) {
    header('HTTP/1.0 403 Forbidden');

    exit('You are not allowed to access this file.');
}

/**
 * @var Composer\Autoload\ClassLoader $loader
 */
$loader = require __DIR__ . '/../app/autoload.php';

// Enable debug
Debug::enable();

// Register error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Create new AppKernel
$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

// Create new request
$request = Request::createFromGlobals();

// Create response and send it back
$response = $kernel->handle($request);
$response->send();

// Terminate current request/response cycle
$kernel->terminate($request, $response);
