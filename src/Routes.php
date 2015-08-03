<?php

/**
 * Routes
 * 
 * Stored by method, query path, controller / handler method or closure.
 */

return [
    // Backend
    ['GET', '/dashboard', ['Microblog\Controllers\Backend\Dashboard', 'show']],
    ['GET', '/login', ['Microblog\Controllers\Backend\Authentication', 'showLogin']],
    ['POST', '/login', ['Microblog\Controllers\Backend\Authentication', 'login']],
    ['GET', '/logout', ['Microblog\Controllers\Backend\Authentication', 'logout']],
    ['GET', '/register', ['Microblog\Controllers\Backend\Authentication', 'showRegister']],
    ['POST', '/register', ['Microblog\Controllers\Backend\Authentication', 'register']],

    // Frontend
    ['GET', '/', ['Microblog\Controllers\Frontend\Page', 'showIndex']],
    ['GET', '/{slug}', ['Microblog\Controllers\Frontend\Page', 'show']]
];
