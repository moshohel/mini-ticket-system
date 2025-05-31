<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/token.php';

class AuthController
{
    public function register($data)
    {
        $user = new User();
        $existing = $user->findByEmail($data['email']);

        if ($existing) {
            jsonResponse(['error' => 'Email already exists'], 400);
        }

        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->create($data['name'], $data['email'], $hashed, $data['role']);
        jsonResponse(['message' => 'User registered']);
    }

    public function login($data)
    {
        $user = new User();
        $found = $user->findByEmail($data['email']);

        if (!$found || !password_verify($data['password'], $found['password_hash'])) {
            jsonResponse(['error' => 'Invalid credentials'], 401);
        }

        $token = generateToken();
        saveToken($token, ['id' => $found['id'], 'role' => $found['role']]);

        jsonResponse(['message' => 'Login successful', 'token' => $token]);
    }

    public function logout($headers)
    {
        if (!isset($headers['Authorization'])) {
            jsonResponse(['error' => 'Token missing'], 401);
        }

        $token = trim(str_replace('Bearer', '', $headers['Authorization']));
        deleteToken($token);

        jsonResponse(['message' => 'Logged out']);
    }
}
