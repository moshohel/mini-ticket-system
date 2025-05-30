<?php

function generateToken()
{
    return bin2hex(random_bytes(32));
}

function saveToken($token, $user)
{
    $tokens = json_decode(file_get_contents(__DIR__ . '/../storage/tokens.json'), true);
    $tokens[$token] = $user;
    file_put_contents(__DIR__ . '/../storage/tokens.json', json_encode($tokens));
}

function deleteToken($token)
{
    $tokens = json_decode(file_get_contents(__DIR__ . '/../storage/tokens.json'), true);
    unset($tokens[$token]);
    file_put_contents(__DIR__ . '/../storage/tokens.json', json_encode($tokens));
}

function getUserFromToken($token)
{
    $tokens = json_decode(file_get_contents(__DIR__ . '/../storage/tokens.json'), true);
    return $tokens[$token] ?? null;
}
