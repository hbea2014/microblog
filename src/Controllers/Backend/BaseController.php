<?php namespace Microblog\Controllers\Backend;

use Microblog\Config;
use Microblog\Redirect;
use Microblog\Encryption\Hash;
use Microblog\Form\Validate;
use Microblog\Form\Token;
use Microblog\Db\UserMapper;
use Microblog\Db\UserSessionMapper;
use Microblog\Models\User;
use Microblog\Models\UserSession;
use Microblog\Template\BackendTwigRenderer;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Abstract class for backend controllers
 * 
 * Incorporates the authentication and redirection of users
 * 
 * @todo I think this class is way too big but I have a hard time simplifying it.
 * I thought about putting the authentication in a seperate class but the problem
 * is then that this authentication class could need the response class to perform
 * redirects or add data to the response in case of failure / success. It looks like
 * the validation could also be extracted somewhere else as well.
 */
abstract class BaseController
{
    protected $config;
    private $user;
    private $userMapper;
    private $userSession;
    private $userSessionMapper;
    protected $response;
    protected $renderer;
    protected $validate;
    protected $redirect;
    protected $session;
    protected $userLoggedIn = false;
    protected $request;
    protected $token;

    /**
     * @var array The error messages
     */
    protected $errors = [];

    public function __construct(
        Config $config, 
        User $user, 
        UserMapper $userMapper,
        UserSession $userSession,
        UserSessionMapper $userSessionMapper,
        Response $response,
        BackendTwigRenderer $renderer,
        Validate $validate,
        Redirect $redirect,
        Session $session,
        Request $request,
        Token $token
    )
    {
        $this->config = $config;
        $this->user = $user;
        $this->userMapper = $userMapper;
        $this->userSession = $userSession;
        $this->userSessionMapper = $userSessionMapper;
        $this->response = $response;
        $this->renderer = $renderer;
        $this->validate = $validate;
        $this->redirect = $redirect;
        $this->session = $session;
        $this->request = $request;
        $this->token = $token;
    }

    /**
     * Adds an error to the errors
     * 
     * @param string $message The description of the error
     */
    public function addError($message)
    {
        $this->errors[] = $message;
    }

    /**
     * Adds an array of errors to the errors
     * 
     * @param array $errors An array of errors
     */
    public function addErrors(array $errors)
    {
        foreach ($errors as $message) {
            $this->addError($message);
        }
    }
    
    /**
     * Gets the errors
     * 
     * @return array The array of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Resets the array of errors to an empty array
     */
    public function resetErrors()
    {
        $this->errors = [];
    }

    /**
     * Checks if there are any errors
     * 
     * @return boolean
     */
    public function hasErrors()
    {
        return ! empty($this->errors);
    }

    /**
     * Logs the user
     * 
     * @param string $email
     * @param string $password
     * @param string $remember
     * @return \Symfony\Component\HttpFoundation\Cookie|boolean
     * @todo Logging in should happen here or in the Authentication subclass, or 
     * in none of those?
     * @todo This huge list of return false looks like bad programming...
     */
    public function logUserIn($email = null, $password = null, $remember = null)
    {

        // Log user in using email and password
        if (null !== $email && null !== $password) {
            // Find user
            $whereEmail = sprintf('`email` = "%s"', $email);

            // Check the email first
            if ($this->userMapper->findRow($this->user, $whereEmail)) {
                // Email correct, check the password
                $expectedPassword = Hash::make($password, $this->user->getSalt());

                if ($expectedPassword === $this->user->getPassword()) { 
                    // Password correct, store user ID in session
                    $this->session->set(
                        $this->config->get('session/sessionName'),
                        $this->user->getId()
                    );

                    // Set the user as logged in
                    $this->userLoggedIn = true;

                    // Remember user if he so desires
                    if ($remember) {
                        // Create a unique hash
                        $hash = Hash::unique();

                        // Check if there's a user session stored for the given user
                        $whereUserId = sprintf('`userId` = "%s"', $this->user->getId());

                        if ( ! $this->userSessionMapper->findRow($this->userSession, $whereUserId) ) {
                            // Create and store user session with unique hash
                            $this->userSessionMapper->insert(
                                $this->userSession,
                                '`userId`, `hash`', 
                                sprintf('"%s", "%s"', $this->user->getId(), $hash)
                            );
                        } else {
                            $hash = $this->userSession->getHash();
                        }

                        $cookie = new Cookie(
                            $this->config->get('remember/cookieName'), 
                            $hash, 
                            time() + $this->config->get('remember/cookieExpiry'),
                            '/'
                        );

                        return $cookie;
                    }

                    return true;
                } else {
                    $this->addError('Incorrect password.');
                }
            } else {
                $this->addError(sprintf('Unknown user with email "%s".', $email));
            }
        } else {
            // Log the user using the session or the remember cookie
            $rememberCookie = $this->request->cookies->get(
                $this->config->get('remember/cookieName'),
                false // The value returned if the cookie is not found
            );
            $sessionVariableUserId = $this->session->get(
                $this->config->get('session/sessionName'),
                false // The value returned if the cookie is not found
            );

            if ($sessionVariableUserId) {
                // Recreate the user using his id, stored in the session
                $whereUserId = sprintf('`id` = "%s"', $sessionVariableUserId);

                if ($this->userMapper->findRow($this->user, $whereUserId)) {
                    // User found
                    // User populated (see Mapper::findRow)
                    // Set user as logged in
                    $this->userLoggedIn = true;

                    return true;
                }

                return false;
            } else if ($rememberCookie) {
                $whereHash = sprintf('`hash` = "%s"', $rememberCookie);

                if ($this->userSessionMapper->findRow($this->user, $whereHash)) {
                    // UserId found for the given hash
                    // Get the user and populate it
                    $whereUserId = sprintf('`id` = "%s"', $this->userSession->getUserId());

                    if ($this->userMapper->findRow($this->user, $whereUserId)) {
                        // User found
                        // User populated (see Mapper::findRow)
                        // Set user as logged in
                        $this->userLoggedIn = true;

                        return true;
                    }

                    return false;
                }

                return false;
            }

            return false;
        }

        return false;
    }

    public function logUserOut()
    {
        $sessionName = $this->config->get('session/sessionName');
        $sessionVariableUserId = $this->session->get($sessionName, false);

        // Remove userId from session
        if ($sessionVariableUserId) {
            $this->session->remove($sessionName);
        }

        $rememberCookieName = $this->config->get('remember/cookieName');
        $rememberCookie = $this->request->cookies->get($rememberCookieName, false);

        if ($rememberCookie) {
            // Remove the row in the user session table
            $whereHash = sprintf('`hash` = "%s"', $rememberCookie);
            $this->userSessionMapper->delete($this->userSession, $whereHash);
            
            // Remove remember cookie
            $this->redirect->to('login', null, $rememberCookieName);
        }

        $this->redirect->to('login');
    }

    /**
     * Create the alertMessage based
     * 
     * @return string The alert message formated for output
     */
    public function createAlertMessage()
    {
        $alertMessage = '';

        if ($this->hasErrors()) {
            foreach ($this->errors as $error) {
                $alertMessage .= '<p>' . e($error) . '</p>';
            }
        }

        return $alertMessage;
    }

    /**
     * Checks if the user is logged in
     * 
     * @return boolean
     */
    public function userIsLoggedIn()
    {
        return $this->userLoggedIn;
    }

    /**
     * Redirects guest to the given location
     * 
     * @param string $location The location to redirect the user to
     */
    public function redirectGuestTo($location)
    {
        if ( ! $this->userIsLoggedIn() && ! $this->logUserIn() ) {
            $this->redirect->to($location);
        }
    }

    /**
     * Redirect logged in user to the given location
     * 
     * @param string $location The location to redirect the user to
     */
    public function redirectLoggedInUserTo($location)
    {
        if ($this->userIsLoggedIn() || $this->logUserIn()) {
            $this->redirect->to($location);
        }
    }

    public function insertUserIntoDataRenderSetContent($template, $data)
    {
        $data = array_merge($data, [
            'username' => $this->user->getUsername()
        ]);

        $html = $this->renderer->render($template, $data);
        $this->response->setContent($html);
    }
    
}