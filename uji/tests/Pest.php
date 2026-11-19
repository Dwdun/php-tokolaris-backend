<?php

// Autoloader resolution (Root vendor atau subfolder vendor)
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../../vendor/autoload.php';
}

// Setup Test Database Environment (prioritaskan environment Docker jika ada)
$dbHost = getenv('DB_HOST') ?: 'db';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE') ?: 'tokolaris_db';
$dbUser = getenv('DB_USERNAME') ?: 'root';
$dbPass = getenv('DB_PASSWORD') ?: 'rootpassword';

putenv("DB_HOST={$dbHost}");
putenv("DB_PORT={$dbPort}");
putenv("DB_DATABASE={$dbName}");
putenv("DB_USERNAME={$dbUser}");
putenv("DB_PASSWORD={$dbPass}");

// Telemetry Hook Ringan: Mengirim laporan hasil test ke laptop mentor secara otomatis (non-blocking)
afterAll(function () {
    $mentorHost = getenv('MENTOR_DASHBOARD_URL') ?: 'http://127.0.0.1:3000/api/report';
    $passed = test()->getPassedCount();
    $failed = test()->getFailedCount();

    $report = [
        'participant' => getenv('NAMA_PESERTA') ?: 'Peserta_01',
        'passed'      => $passed,
        'failed'      => $failed,
        'timestamp'   => time(),
    ];

    @file_get_contents($mentorHost, false, stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-Type: application/json',
            'content' => json_encode($report),
            'timeout' => 0.2
        ]
    ]));
});
