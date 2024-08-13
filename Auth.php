<?php

require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth
{
    private $pdo;
    private $secretKey;

    public function __construct($pdo, $secretKey)
    {
        $this->pdo = $pdo;
        $this->secretKey = $secretKey;
    }

    public function register($username, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        return $stmt->execute();
    }

    public function login($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT id, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $this->generateJWT($user['id']);
        }

        return null;
    }

    private function generateJWT($userId)
    {
        $payload = [
            'iss' => 'http://yourdomain.com',
            'aud' => 'http://yourdomain.com',
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + (60 * 60), // Token expires in 1 hour
            'data' => [
                'userId' => $userId
            ]
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function validateJWT($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            return (array) $decoded->data;
        } catch (Exception $e) {
            return null;
        }
    }
}
