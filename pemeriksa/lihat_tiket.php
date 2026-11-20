<?php
// pemeriksa/lihat_tiket.php
// Menampilkan informasi spesifikasi tiket secara rapi, bersih, dan ramah terminal (Terminal-Friendly).
// Menghilangkan format markdown mentah (##, **, #) serta menyembunyikan bocoran poin demi integritas blind testing.

$target = isset($argv[1]) ? strtoupper(trim($argv[1])) : '';
if (empty($target)) {
    echo "Sebutkan kode tiket yang ingin dilihat. Contoh: .\\gate.bat lihat T01\n";
    exit(1);
}

// Cari berkas tiket
$files = glob(__DIR__ . "/../tiket/{$target}*.md");
if (empty($files)) {
    echo "Berkas spesifikasi tiket [$target] tidak ditemukan di direktori tiket/.\n";
    exit(1);
}

$content = file_get_contents($files[0]);

// Bersihkan karakter UTF-8 emoji dan non-ascii yang merusak tampilan terminal
$cleanContent = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}\x{1F1E0}-\x{1F1FF}]/u', '', $content);

// Parse baris demi baris
$lines = explode("\n", str_replace("\r", "", $cleanContent));

$title = "Tiket $target";
$foundFirstTitle = false;
$sections = [];
$currentSection = 'PENDAHULUAN';
$inCodeBlock = false;

foreach ($lines as $line) {
    $trimmed = trim($line);

    // Ambil Judul Utama pada heading pertama saja
    if (!$foundFirstTitle && preg_match('/^#\s+(.+)$/', $trimmed, $m)) {
        $title = trim($m[1]);
        $foundFirstTitle = true;
        continue;
    }

    // Deteksi blok kode
    if (str_starts_with($trimmed, '```')) {
        $inCodeBlock = !$inCodeBlock;
        continue;
    }

    // Ambil Heading Section (## Nama Section)
    if (!$inCodeBlock && preg_match('/^##\s+(.+)$/', $trimmed, $m)) {
        $currentSection = strtoupper(trim($m[1]));
        if (!isset($sections[$currentSection])) {
            $sections[$currentSection] = [];
        }
        continue;
    }

    // Sembunyikan informasi bocoran poin untuk menjaga integritas blind testing
    if (stripos($trimmed, 'poin') !== false && (stripos($trimmed, 'pts') !== false || stripos($trimmed, 'bobot') !== false)) {
        continue;
    }

    if (!isset($sections[$currentSection])) {
        $sections[$currentSection] = [];
    }

    $sections[$currentSection][] = $inCodeBlock ? "    " . $line : $line;
}

// Bersihkan judul dari markdown
$cleanTitle = preg_replace('/[#*`]/', '', $title);
$cleanTitle = trim(preg_replace('/^\[?' . preg_quote($target, '/') . '\]?[:\s-]*/i', '', $cleanTitle));

// Format tampilan terminal bersih
echo "======================================================================\n";
echo " SPESIFIKASI TIKET: $target\n";
echo " JUDUL: $cleanTitle\n";
echo "======================================================================\n\n";

foreach ($sections as $secName => $secLines) {
    $text = trim(implode("\n", $secLines));
    if (empty($text)) continue;

    // Bersihkan format markdown tebal dan miring
    $cleanedText = preg_replace('/\*\*(.*?)\*\*/', '$1', $text);
    $cleanedText = preg_replace('/\*(.*?)\*/', '$1', $cleanedText);
    $cleanedText = preg_replace('/`{1,2}(.*?)`{1,2}/', '$1', $cleanedText);

    echo "[$secName]\n";
    $subLines = explode("\n", $cleanedText);
    foreach ($subLines as $sl) {
        $slTrim = trim($sl);
        if ($slTrim === '---') continue;
        
        // Format bullet point yang rapi
        if (str_starts_with($slTrim, '* ') || str_starts_with($slTrim, '- ')) {
            echo "  - " . substr($slTrim, 2) . "\n";
        } elseif (preg_match('/^\d+\.\s+(.+)$/', $slTrim, $matches)) {
            echo "  " . $matches[0] . "\n";
        } elseif (str_starts_with($sl, '    ')) {
            echo "    " . trim($sl) . "\n";
        } elseif (!empty($slTrim)) {
            echo "  " . $slTrim . "\n";
        } else {
            echo "\n";
        }
    }
    echo "\n";
}

echo "----------------------------------------------------------------------\n";
echo " CARA MENGUJI TIKET INI:\n";
echo "   .\\gate.bat uji $target    (di Windows)\n";
echo "   ./gate uji $target        (di Linux / macOS)\n";
echo "======================================================================\n";
