<?php

require_once 'vendor/autoload.php';
include_once './Auth.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=authjwt', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $auth = new Auth($pdo, 'your_secret_key');

    // Register a new user
    $auth->register('username', 'password');

    // Login and generate JWT
    $token = $auth->login('username', 'password');
    if ($token) {
        echo "JWT Token: " . $token;

        $decodedData = $auth->validateJWT($token);
        if ($decodedData) {
            echo "<br><br>User ID: " . $decodedData['userId'];
        } else {
            echo "Invalid token!";
        }

    } else {
        echo "Invalid credentials!";
    }

    
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
