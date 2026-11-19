#!/usr/bin/env bash
# pemeriksa/gate.sh
# Antarmuka CLI Peserta & Mentor untuk memantau tiket dan alur Gate.
# Mendukung 100% OFFLINE (tanpa Wi-Fi, tanpa layar proyektor).
#
# Penggunaan Peserta:
#   ./gate status                Lihat papan skor progres tiket lokal
#   ./gate lihat                 Lihat status seluruh tiket
#   ./gate lihat T04             Lihat checklist gate & detail tiket T04
#   ./gate mulai T04             Mulai mengambil tiket T04 (WIP limit: max 2)
#   ./gate uji T04               Jalankan pengujian otomatis untuk tiket T04
#   ./gate uji B01               Jalankan pengujian tiket bonus B01
#   ./gate cek                   Cek cepat 25 tiket + 5 bonus (uji anti-regresi)
#   ./gate reset                 Kembalikan database ke data awal (Fresh State)
#   ./gate kirim                 (Opsional jika ada Wi-Fi) Kirim progres ke Papan Mentor

set -euo pipefail

PAPAN_URL=${PAPAN_URL:-"http://127.0.0.1:3000/api/progres"}
PESERTA=${NAMA_PESERTA:-"Peserta_Meja01"}

perintah=${1:-status}
target=${2:-""}

# Deteksi lokasi binary pest
pest_bin="vendor/bin/pest"
if [[ ! -f "$pest_bin" ]] && [[ -f "app/vendor/bin/pest" ]]; then
  pest_bin="app/vendor/bin/pest"
fi

case "$perintah" in
  status|cek)
    echo "=========================================================="
    echo " PAPAN SKOR PROGRES PESERTA: $PESERTA"
    echo "=========================================================="
    php pemeriksa/cek_cepat.php
    ;;

  lihat)
    if [[ -z "$target" ]]; then
      php pemeriksa/cek_cepat.php
      echo ""
      echo "Gunakan: ./gate lihat [KODE_TIKET] untuk melihat detail gate (cth: ./gate lihat T04)."
    else
      echo "=========================================================="
      echo " DETAIL GATE TIKET: $target"
      echo "=========================================================="
      if compgen -G "tiket/${target}*.md" > /dev/null; then
        cat tiket/${target}*.md
      else
        echo "File tiket untuk $target tidak ditemukan di folder tiket/."
      fi
    fi
    ;;

  mulai)
    if [[ -z "$target" ]]; then
      echo "❌ Sebutkan kode tiket. Contoh: ./gate mulai T04"
      exit 1
    fi
    echo "[+] Memulai tiket $target..."
    echo "[M1] Pastikan baseline awal sudah pernah dijalankan."
    echo "[M4] Menjalankan test awal untuk membuktikan cacat..."
    
    # Deteksi apakah tiket reguler (Txx) atau bonus (Bxx)
    nomor=${target#T}
    nomor=${nomor#B}
    test_pattern="uji/tests/Tickets/*${nomor}*Test.php"
    
    if compgen -G "$test_pattern" > /dev/null; then
      $pest_bin $test_pattern || true
    else
      echo "File test $test_pattern belum ditemukan."
    fi
    echo ""
    echo "💡 Silakan pelajari instruksi di tiket/${target}*.md dan materi panduan."
    ;;

  uji)
    if [[ -z "$target" ]]; then
      echo "❌ Sebutkan kode tiket. Contoh: ./gate uji T04 atau ./gate uji B01"
      exit 1
    fi
    echo "[+] Menjalankan pengujian otomatis untuk $target..."
    nomor=${target#T}
    nomor=${nomor#B}
    test_pattern="uji/tests/Tickets/*${nomor}*Test.php"

    if compgen -G "$test_pattern" > /dev/null; then
      $pest_bin $test_pattern
    else
      echo "❌ File test $test_pattern tidak ditemukan."
      exit 1
    fi
    ;;

  reset)
    echo "=========================================================="
    echo " RESET DATABASE TOKOLARIS (FRESH STATE)"
    echo "=========================================================="
    php pemeriksa/reset_db.php
    ;;

  kirim)
    echo "[+] Mengirim status pengujian lokal ke Papan Proyektor ($PAPAN_URL)..."
    php pemeriksa/kirim.php || echo "⚠️ Gagal kirim ke server papan (Mode Offline aktif - gunakan ./gate status untuk pantau lokal)"
    ;;

  *)
    echo "Perintah tidak dikenal: $perintah"
    echo "Perintah yang tersedia:"
    echo "  ./gate status             Lihat total poin & tabel progres tiket lokal"
    echo "  ./gate lihat [TIKET]      Lihat spek & instruksi gate tiket"
    echo "  ./gate mulai [TIKET]      Mulai pengerjaan & jalankan test awal"
    echo "  ./gate uji [TIKET]        Jalankan unit test spesifik tiket"
    echo "  ./gate cek                Uji cepat anti-regresi seluruh tiket"
    echo "  ./gate reset              Kembalikan database ke kondisi awal bersih"
    echo "  ./gate kirim              Kirim skor ke papan pusat (jika terhubung)"
    exit 2
    ;;
esac
