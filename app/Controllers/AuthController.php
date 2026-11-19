<?php

namespace App\Controllers;

use App\Support\Database;

class AuthController {

    /**
     * TKT-19: Autentikasi Login
     * ❌ BUG: Masih memverifikasi hash MD5 lama tanpa mekanisme Rehash-on-Login!
     */
    public function login(string $email, string $plainPassword): array {
        $user = Database::query("SELECT * FROM users WHERE email = ?", [$email])[0] ?? null;

        if (!$user) {
            return ['status' => 'error', 'code' => 401, 'message' => 'Email atau password salah'];
        }

        // ❌ BUG: Hanya mencocokkan hash MD5 tanpa meng-upgrade ke bcrypt/Argon2
        if (md5($plainPassword) === $user['password']) {
            return [
                'status' => 'success',
                'code' => 200,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ]
            ];
        }

        return ['status' => 'error', 'code' => 401, 'message' => 'Email atau password salah'];
    }
}
