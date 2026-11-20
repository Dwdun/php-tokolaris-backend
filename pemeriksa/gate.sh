#!/usr/bin/env bash
# pemeriksa/gate.sh
# Antarmuka CLI Peserta untuk verifikasi tiket dan sinkronisasi otomatis ke Mentor.
# Mode Blind: Tidak membocorkan total tiket atau akumulasi poin kepada peserta.

set -euo pipefail

perintah=${1:-status}
target=${2:-""}

pest_bin="vendor/bin/pest"
if [[ ! -f "$pest_bin" ]] && [[ -f "app/vendor/bin/pest" ]]; then
  pest_bin="app/vendor/bin/pest"
fi

case "$perintah" in
  status)
    php pemeriksa/cek_cepat.php
    ;;

  cek)
    if [[ -z "$target" ]]; then
      php pemeriksa/cek_cepat.php
    else
      php pemeriksa/cek_cepat.php "$target"
    fi
    ;;

  lihat)
    if [[ -z "$target" ]]; then
      php pemeriksa/cek_cepat.php
      echo ""
      echo "Gunakan: ./gate lihat [KODE_TIKET] untuk melihat lembar tiket (contoh: ./gate lihat T04)."
    else
      echo "=========================================================="
      echo " DETAIL SPESIFIKASI TIKET: $target"
      echo "=========================================================="
      if compgen -G "tiket/${target}*.md" > /dev/null; then
        cat tiket/${target}*.md
      else
        echo "File spesifikasi tiket $target belum ditemukan di folder tiket/."
      fi
    fi
    ;;

  mulai)
    if [[ -z "$target" ]]; then
      echo "Sebutkan kode tiket yang ingin diambil. Contoh: ./gate mulai T04"
      exit 1
    fi
    echo "[+] Memulai pengerjaan tiket $target..."
    echo "[+] Menjalankan uji awal baseline untuk mereproduksi cacat kode..."
    
    nomor=${target#T}
    nomor=${nomor#B}
    test_pattern="uji/tests/Tickets/*${nomor}*Test.php"
    
    if compgen -G "$test_pattern" > /dev/null; then
      $pest_bin $test_pattern || true
    fi
    echo ""
    echo "Silakan pelajari lembar instruksi di tiket/${target}*.md dan terapkan solusinya."
    ;;

  uji)
    if [[ -z "$target" ]]; then
      echo "Sebutkan kode tiket yang diuji. Contoh: ./gate uji T04 atau ./gate uji B01"
      exit 1
    fi
    # 1. Jalankan verifikasi fungsional & telemetri senyap ke server mentor
    php pemeriksa/cek_cepat.php "$target"
    
    # 2. Jalankan juga Pest assertion jika file test tersedia
    nomor=${target#T}
    nomor=${nomor#B}
    test_pattern="uji/tests/Tickets/*${nomor}*Test.php"
    if compgen -G "$test_pattern" > /dev/null; then
      echo ""
      echo "[+] Menjalankan Pest Automated Unit & Integration Tests..."
      $pest_bin $test_pattern || true
    fi
    ;;

  reset)
    echo "=========================================================="
    echo " RESET DATABASE TOKOLARIS (FRESH STATE)"
    echo "=========================================================="
    php pemeriksa/reset_db.php
    ;;

  export)
    php pemeriksa/export_hasil.php
    ;;

  *)
    echo "Perintah tidak dikenali: $perintah"
    echo "Penggunaan: ./gate [status | lihat | mulai | uji | cek | reset | export] [KODE_TIKET]"
    exit 1
    ;;
esac
