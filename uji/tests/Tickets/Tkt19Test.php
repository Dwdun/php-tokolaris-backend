<?php

use App\Controllers\AuthController;
use App\Support\Database;

it('successfully authenticates and transparently upgrades legacy MD5 hash to bcrypt on login', function () {
    $controller = new AuthController();
    $email = 'budi@example.com';
    $password = 'password123';

    // 1. Login user
    $response = $controller->login($email, $password);
    expect($response['code'])->toBe(200);

    // 2. Periksa apakah password di database otomatis ter-upgrade ke bcrypt (panjang string 60 karakter)
    $user = Database::query("SELECT password FROM users WHERE email = ?", [$email])[0];
    
    // Hash bcrypt selalu diawali dengan $2y$ dan berpanjang 60 karakter
    expect($user['password'])->toStartWith('$2y$')
        ->and(strlen($user['password']))->toBe(60);
});

it('allows future logins using the newly migrated bcrypt hash', function () {
    $controller = new AuthController();
    $email = 'budi@example.com';

    // Login kedua kali setelah hash diupgrade ke bcrypt
    $response = $controller->login($email, 'password123');
    expect($response['code'])->toBe(200);

    // Coba password yang salah
    $failed = $controller->login($email, 'wrongpassword');
    expect($failed['code'])->toBe(401);
});
