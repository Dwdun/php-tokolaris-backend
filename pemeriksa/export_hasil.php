<?php
// pemeriksa/export_hasil.php
// Membungkus seluruh hasil evaluasi tiket peserta ke file hasil_peserta.json

require_once __DIR__ . '/../app/Support/Database.php';

use App\Support\Database;

ob_start();
require __DIR__ . '/cek_cepat.php';
$output = ob_get_clean();

// Parse status tiket dari output cek_cepat
preg_match_all('/(T\d{2}|B\d{2})\s*\|.*?\|\s*(\033\[32m🟢 LULUS|\033\[32m⭐ SELESAI|\033\[31m🔴 BELUM|\033\[33m⏳ TERKUNCI|LULUS|GAGAL)/u', $output, $matches, PREG_SET_ORDER);

$detail = [];
$totalLulus = 0;
$totalPoin = 0;

foreach ($matches as $m) {
    $kode = trim($m[1]);
    $isLulus = str_contains($m[2], 'LULUS') || str_contains($m[2], 'SELESAI');
    $detail[$kode] = $isLulus ? 'LULUS' : 'GAGAL';
    if ($isLulus) {
        $totalLulus++;
        $totalPoin += str_starts_with($kode, 'B') ? 30 : 10;
    }
}

$exportData = [
    'peserta' => getenv('NAMA_PESERTA') ?: 'Peserta_Meja01',
    'meja' => getenv('NOMOR_MEJA') ?: 'Meja 01',
    'role_target' => 'Junior / Mid Backend Engineer',
    'tiket_lulus' => $totalLulus,
    'total_poin' => $totalPoin,
    'waktu' => date('c'),
    'detail' => $detail
];

$outputFile = __DIR__ . '/../hasil_peserta.json';
file_put_contents($outputFile, json_encode($exportData, JSON_PRETTY_PRINT));

echo "========================================================\n";
echo " BUNDLE HASIL EVALUASI PESERTA BERHASIL DIBUAT\n";
echo "========================================================\n";
echo "File tersimpan di: hasil_peserta.json\n";
echo "Total Tiket Lulus : $totalLulus / 25\n";
echo "Total Poin        : $totalPoin Pts\n";
echo "\nFile ini siap diserahkan ke mentor untuk di-import ke GUI Dashboard Penilaian & AI Report!\n";
echo "========================================================\n";
