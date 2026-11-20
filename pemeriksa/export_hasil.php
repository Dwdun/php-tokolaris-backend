<?php
// pemeriksa/export_hasil.php
// Membungkus seluruh hasil evaluasi tiket peserta ke file hasil_peserta.json

$argv = ['cek_cepat.php', '--all'];
$exportData = require __DIR__ . '/cek_cepat.php';

$outputFile = __DIR__ . '/../hasil_peserta.json';
file_put_contents($outputFile, json_encode($exportData, JSON_PRETTY_PRINT));

echo "======================================================================\n";
echo "              EKSPOR HASIL EVALUASI TIKET PESERTA                     \n";
echo "======================================================================\n";
echo "Berkas berhasil dibuat: hasil_peserta.json\n";
echo "Waktu ekspor: " . date('Y-m-d H:i:s') . "\n";
echo "======================================================================\n";
