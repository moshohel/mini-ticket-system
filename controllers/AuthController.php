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
            jsonResponse(null, 'Email already exists', 400, 'Duplicate email');
        }

        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->create($data['name'], $data['email'], $hashed, $data['role']);
        jsonResponse(null, 'User registered successfully', 201);
    }

    public function login($data)
    {
        $user = new User();
        $found = $user->findByEmail($data['email']);

        if (!$found || !password_verify($data['password'], $found['password_hash'])) {
            jsonResponse(null, 'Invalid email or password', 401, 'Authentication failed');
        }

        $token = generateToken();
        saveToken($token, ['id' => $found['id'], 'role' => $found['role']]);
        jsonResponse(['token' => $token], 'Login successful', 200);
    }

    public function logout($headers)
    {
        if (!isset($headers['Authorization'])) {
            jsonResponse(['error' => 'Token missing'], 401);
        }

        $token = trim(str_replace('Bearer', '', $headers['Authorization']));
        deleteToken($token);

        jsonResponse(null, 'Logged out successfully', 200);
    }
}
