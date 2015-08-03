<?php namespace Microblog\Form;

use Symfony\Component\HttpFoundation\Session\Session;

/** Token class
 * 
 * Provides a basic Cross Site Request Forgery (CSRF) protection
 */
class Token
{

    /**
     * Generate a unique token and store it in the current session
     * 
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param string $tokenName The name of the session variable holding the token
     * @return string The unique token
     */
    public static function generate(Session $session, $tokenName)
    {
        $uniqueToken = md5(uniqid());

        $session->set($tokenName, $uniqueToken);

        return $uniqueToken;
    }

    /**
     * Check a token string agains the token stored in the session
     * 
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param string $tokenName The name of the session variable holding the token
     * @param string $tokenToCheck The string to check against the token generated
     * earlier and stored in the session
     * @return boolean
     */
    public static function check(Session $session, $tokenName, $tokenToCheck)
    {
        $tokenFromSession = $session->get($tokenName, false);

        if ($tokenFromSession === $tokenToCheck) {
            $session->remove($tokenName);

            return true;
        }

        return false;
    }
}