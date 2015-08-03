<?php namespace Microblog;

/**
 * Bootstrap script
 *
 * Takes care of: 
 *  - autoloadig
 *  - error reporting and handling
 *  - dependendy injection
 *  - routing and responses
 *
 * @todo Set the namespace in the bootstrap file?
 */

use FastRoute;
use Symfony\Component\HttpFoundation\Response;

// Autoloading using Composer's autoloader
require __DIR__ . '/../vendor/autoload.php';

/**
 * Get general functions
 * 
 * @todo Remove the sanitize function and use twig to sanitize?
 * @todo Using static helper classes to have common and useful functions available in the whole script?
 */
require_once __DIR__ . '/functions/sanitize.php';


/**
 * Dependency injection
 * 
 * Inject dependencies using auryn.
 * 
 * @see https://github.com/rdlowrey/Auryn
 */
// Get injector
$injector = include('Dependencies.php');


/**
 * Configuration
 */
// Get the instance of the config class and share it through the app
$config = $injector->make('Microblog\Config', [':configFilePath' => dirname(__DIR__) . '/config.yaml']);
$injector->share($config);


/**
 * Error reporting and handling
 */
// Set php to display all errors
error_reporting(E_ALL);

// Set the environment
$environment = $config->get('environment');

// Register the error handler
// @see https://github.com/filp/whoops
$whoops = new \Whoops\Run;

// Display errors using Whoops only when not in production
if ('production' !== $environment) {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    // Display an error page in production
    $whoops->pushHandler(function($e) {
        // @todo Create that error page
        echo 'Friendly error page and send an email to the developer';
    });
}

// Set Whoops as default error and exception handler
$whoops->register();


/**
 * Authentication: Session
 */
$session = $injector->make('Symfony\Component\HttpFoundation\Session\Session');
$session->start();
$injector->share($session);

// Start a session
//session_start();


/**
 * Routing and responses
 * 
 * Get requests and send responses with the Symfony HttpFoundation component, 
 * route requests using FastRoute, dispatching them to the correct controller 
 * / handler or closure.
 * We don't use MVC here, but we stick with the separation of concerns.
 * 
 * @see https://github.com/symfony/HttpFoundation
 * @see https://github.com/nikic/FastRoute
 * @see http://blog.ircmaxell.com/2014/11/a-beginners-guide-to-mvc-for-web.html
 * @see https://en.wikipedia.org/wiki/Separation_of_concerns
 */

// Create request object from globals
$request = $injector->execute('Symfony\Component\HttpFoundation\Request::createFromGlobals');

// Create response object
$response = $injector->make('Symfony\Component\HttpFoundation\Response');

// Share request and response instances
// @see https://github.com/rdlowrey/Auryn#instance-sharing
$injector->share($request);
$injector->share($response);

// Define and add routes to the route collection
$routeDefinitionCallback = function(FastRoute\RouteCollector $r) {
    // Get routes
    $routes = include('Routes.php');

    // Add routes to the collection
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

// Create a dispatcher with the route collection
$dispatcher = FastRoute\simpleDispatcher($routeDefinitionCallback);

// Dispatch to the corresponding route based on the method and the path
$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

// Set the response content and status code 
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $response->setContent('404 - Couldn\'t find that!');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed!');
        $response->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
        break;
    case FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        // Instantiate the corresponding class
        $class = $injector->make($className);
        $class->$method($vars);
        break;
}

// Check and send response
$response->prepare($request);
$response->send();
