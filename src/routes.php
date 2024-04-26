<?php

return function ($router) {

    // router yönetimi için API rotaları
    // API routes grouped under '/api'
    $router->group('api/', function ($router) {
        // Using camelCase for method names
        // http://localhost/api/users
        $router->get('users', 'UserController', 'getAllUsers');
        // http://localhost/api/user/1
        $router->get('user/{id}', 'UserController', 'getUserById');
        // http://localhost/api/users
        $router->post('users','UserController', 'createUser');
    });

    // Kimlik doğrulama ile ilgili rotalar
    $router->group('auth/', function ($router) {
        $router->post('dashboard', 'HomeController', 'welcomeUser');
    });
    $router->addMiddleware('auth', 'getAuthenticate');

    // http://localhost/login
    $router->post('login', 'AuthController', 'login');

    // http://localhost/register
    $router->post('register', 'AuthController', 'register');

    // http://localhost/forgotPassword
    $router->post('newpassword', 'AuthController', 'forgotPassword');
    
    // http://localhost/resetPassword/84b8a02e2832b5d7e9897762abe0828ba6b20687d582ad2f0b83e7398917fb48b947dfe1955713bbf4a837f08870b0035cbe
    $router->get('resetPassword/{token}', 'AuthController', 'resetPassword');

    $router->get('secret', 'HomeController', 'secured');
    $router->get('users', 'UserController', 'getAllUsers');
    $router->get('user/{id}', 'UserController', 'getUserById');
    $router->get('install', 'HomeController', 'install');
    
    // Genel erişim rotaları
    $router->get('', 'HomeController', 'hi');

    $router->addMiddleware('home', 'getAuthenticate');
    $router->addMiddleware('install', 'getAuthenticate');
};
