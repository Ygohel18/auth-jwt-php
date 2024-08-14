<?php

require_once 'vendor/autoload.php';

// Angrybird :)

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Start session
session_start();

// Load routes
$routes = require 'routes/web.php';

// Get the requested path
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/auth-jwt-php', '', $path);  //Only if you are running on localhost


// Configure Twig
$loader = new FilesystemLoader('templates');
$twig = new Environment($loader, [
    'cache' => false,
    'debug' => true
]);

// Route the request
if (array_key_exists($path, $routes)) {

    error_log("Requested path: " . $path);
    error_log("Available routes: " . print_r($routes, true));

    echo $twig->render($routes[$path]['template'], [
        'title' => $routes[$path]['name'],
        'current_path' => $path,
        'routes' => $routes,
    ]);
} else {
    // Handle 404
    echo $twig->render('404.html.twig', [
        'title' => '404 Page Not Found',
        'current_path' => $path,
        'routes' => $routes,
    ]);
}
