<?php

use App\Support\Database;

it('validates all phone numbers in users table adhere strictly to E.164 standard (+62...) format', function () {
    $users = Database::query("SELECT phone FROM users WHERE phone IS NOT NULL AND phone != ''");

    foreach ($users as $user) {
        $phone = $user['phone'];
        // Harus diawali dengan +62 dan hanya berisi digit angka setelah tanda +
        expect($phone)->toStartWith('+62')
            ->and(preg_match('/^\+62[0-9]{8,13}$/', $phone))->toBe(1);
    }
});
