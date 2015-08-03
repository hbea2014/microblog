<?php namespace Microblog;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Redirect class
 */
class Redirect
{
    private $config;
    private $redirectResponse;

    /**
     * @param \Microblog\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Redirects to the given location, setting and deleting a cookie if needed
     * 
     * @param string $location
     * @param null|Symfony\Component\HttpFoundation\Cookie $cookie
     * @param string $nameOfCookieToRemove The name of the cookie to remove in 
     * the browser
     * @todo Is there a way to instantiate the RedirectResponse somewhere else?
     * I couldn't manage to store it into a property because the RedirectResponse
     * was sent directly after instantiation...
     */
    public function to($location, $cookie = null, $nameOfCookieToRemove = null)
    {
        $appUrl = $this->config->get('appUrl');
        $this->redirectResponse = new RedirectResponse($appUrl . '/' . $location);

        if (null !== $cookie) {
            $this->redirectResponse->headers->setCookie($cookie);
        } 

        if (null !== $nameOfCookieToRemove) {
            $this->redirectResponse->headers->clearCookie($nameOfCookieToRemove);
        }

        $this->redirectResponse->send();
    }
}
