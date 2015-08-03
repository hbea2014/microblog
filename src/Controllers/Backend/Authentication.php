<?php namespace Microblog\Controllers\Backend;

use Microblog\Form\Input;
use Microblog\Form\Token;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Authentication Controller / Handler class
 * 
 * Controller / handler class dealing with the authentication
 */
class Authentication extends BaseController
{

    /**
     * Shows the login form to guests, redirects logged in users to the
     * dashboard
     */
    public function showLogin()
    {
        $this->redirectLoggedInUserTo('dashboard');

        $data = [
            'title' => 'Log in',
            'token' => Token::generate(
                $this->session,
                $this->config->get('session/tokenName')
            )
        ];

        if ($this->hasErrors()) {
            $data['alertMessage'] = $this->createAlertMessage();
        }

        $html = $this->renderer->render('login/layout', $data);
        $this->response->setContent($html);
    }


    /**
     * Logs the user in, redirects to the dashboard if success, displays the 
     * errors otherwise
     * 
     * @todo Display login errors in case of login error (ie. validation passed 
     * but login itself fails)
     * @see Microblog\Controllers\Backend\BaseController::logUserIn
     */
    public function login()
    {
        if (Input::exists()) {
            if (Token::check($this->config->get('session/tokenName'), Input::get('token'))) {

                $validation = $this->validate->check($_POST, [
                    'email' => [
                        'required' => true,
                        'min' => 2
                    ],
                    'password' => [
                        'required' => true,
                        'min' => 2
                    ]
                ]);

                if ($validation->passed()) {
                    // Log user in
                    $remember = ('on' === Input::get('remember')) ? true : false;

                    $login = $this->logUserIn(
                        Input::get('email'),
                        Input::get('password'),
                        $remember
                    );

                    if ($login instanceof Cookie) {
                        // Redirect and set the remember cookie
                        $this->redirect->to('dashboard', $login);
                    } else if (true === $login) {
                        // Redirect to the dashboard
                        $this->redirect->to('dashboard');
                    } else {
                        // Display the login form again
                        $this->showLogin();
                    }

                } else {
                    $this->addErrors($validation->errors());

                    $this->showLogin();
                }
            }
        }
    }


    /**
     * Shows the register form to guests, redirects logged in users to the
     * dashboard
     */
    public function showRegister()
    {
        $this->redirectLoggedInUserTo('dashboard');

        $data = [
            'title' => 'Register',
            'token' => Token::generate(
                $this->session,
                $this->config->get('session/tokenName')
            )
        ];

        if ($this->hasErrors()) {
            $data['alertMessage'] = $this->createAlertMessage();
        }

        $html = $this->renderer->render('register/layout', $data);
        $this->response->setContent($html);
    }

    public function register()
    {
    }

    /**
     * Logs the user out
     * 
     * @see Microblog\Controllers\Backend\BaseController::logUserOut
     */
    public function logout()
    {
        $this->logUserOut();
    }
}
