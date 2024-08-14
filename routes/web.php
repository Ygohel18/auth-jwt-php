<?php

$routes = [

    '/' => [
        'template' => 'index.html.twig', 
        'name' => 'Admin Dashboard', 
        'url' => '/'
    ],

    '/home' => [
        'template' => 'index.html.twig', 
        'name' => 'Dashboard', 
        'url' => '/home'
    ],

    // User Management Routes
    '/user/new' => [
        'template'   => 'add-user.html.twig',
        'name'       => 'New User',
        'url'        => '/user/new',
        'middleware' => ['auth'],
    ],

    '/404' => [
        'template' => '404.html.twig', 
        'name' => '404 Page not found', 
        'url' => '/404'
    ],
];

return $routes;
