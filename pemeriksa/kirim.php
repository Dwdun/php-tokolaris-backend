<?php
// pemeriksa/kirim.php
// Mengirim progres hasil pengujian peserta ke papan pusat mentor via Wi-Fi lokal

$papanUrl = getenv('PAPAN_URL') ?: 'http://127.0.0.1:3000/api/progres';
$peserta  = getenv('NAMA_PESERTA') ?: 'Peserta_01';
$meja     = getenv('NOMOR_MEJA') ?: 'Meja_01';

ob_start();
require __DIR__ . '/cek_cepat.php';
$output = ob_get_clean();

// Parse status tiket dari output cek_cepat
preg_match_all('/(T\d{2}|B\d{2})\s*\|.*?\|\s*(\033\[32m🟢 LULUS|\033\[32m⭐ SELESAI|\033\[31m🔴 BELUM|\033\[33m⏳ TERKUNCI|LULUS|GAGAL)/u', $output, $matches, PREG_SET_ORDER);

$hasil = [];
$totalLulus = 0;

foreach ($matches as $m) {
    $kode = trim($m[1]);
    $statusText = strip_tags($m[2]);
    $isLulus = str_contains($m[2], 'LULUS') || str_contains($m[2], 'SELESAI');
    $hasil[$kode] = $isLulus ? 'LULUS' : 'GAGAL';
    if ($isLulus) $totalLulus++;
}

$payload = json_encode([
    'peserta'     => $peserta,
    'meja'        => $meja,
    'tiket_lulus' => $totalLulus,
    'detail'      => $hasil,
    'waktu'       => date('c')
]);

$ctx = stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\n",
        'content' => $payload,
        'timeout' => 2.0
    ]
]);

$response = @file_get_contents($papanUrl, false, $ctx);
if ($response !== false) {
    echo "✅ Berhasil terkirim ke papan pusat! ($totalLulus tiket lulus)\n";
} else {
    echo "⚠️ Papan pusat tidak terjangkau di $papanUrl. Pastikan terhubung ke Wi-Fi ruangan.\n";
}
