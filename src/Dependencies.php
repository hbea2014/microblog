<?php

/**
 * Dependency injection
 * 
 * Using Auryn as dependency injector. Setting the classes for automatic 
 * provisioning.
 * 
 * @see https://github.com/rdlowrey/Auryn
 */

namespace Microblog;

$injector = new \Auryn\Injector;

/**
 * Config
 */
$injector->alias('Config', 'Microblog\Config');


/**
 * Session
 */
$injector->alias('Session', 'Symfony\Component\HttpFoundation\Session');


/**
 * Request and response
 */
$injector->alias('Request', 'Symfony\Component\HttpFoundation\Request');
$injector->alias('Response', 'Symfony\Component\HttpFoundation\Response');
$injector->alias('RedirectResponse', 'Symfony\Component\HttpFoundation\RedirectResponse');
// Set a dummy URL for class instantiation
$injector->define('RedirectResponse', [':url' => 'http://localhost']);
$injector->alias('Redirect', 'Microblog\Redirect');


/**
 * Template engine
 */
// Twig as a template engine / renderer
$injector->alias('Microblog\Template\RendererInterface', 'Microblog\Template\TwigRenderer');
// Give Twig the responsiblity to create the class to a function
$injector->delegate('Twig_Environment', function() use ($injector) {
    // @todo Instantiate the two following classes with the injector?
    $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
    $twig = new \Twig_Environment($loader);

    return $twig;
});

// Set the FrontendRenderer
$injector->alias('Microblog\Template\FrontendRendererInterface', 'Microblog\Template\FrontendTwigRenderer');


/**
 * Data mapper
 */
// Set PageTable as DbTable for the PageTableMapper
//$injector->define('Microblog\Db\PageTableMapper', ['dbTable' => 'Microblog\Db\PageTable']);
$injector->alias('PageModel', 'Microblog\Models\Page');

// UserSession
//$injector->define('Microblog\Db\UserSessionTableMapper', ['dbTable' => 'Microblog\Db\UserSessionTable']);

return $injector;
