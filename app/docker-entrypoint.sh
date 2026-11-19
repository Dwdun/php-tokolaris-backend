#!/bin/bash
set -e

# If vendor directory does not exist in /var/www, copy from pre-built /opt/vendor
if [ ! -d "/var/www/vendor" ] && [ -d "/opt/vendor" ]; then
    echo "[Entrypoint] Menyalin paket dependensi vendor dari cache internal..."
    cp -r /opt/vendor /var/www/vendor
fi

# Fallback: jika vendor masih belum ada, coba jalankan composer install
if [ ! -f "/var/www/vendor/autoload.php" ] && [ -f "/var/www/composer.json" ]; then
    echo "[Entrypoint] Menginstall dependensi composer..."
    cd /var/www && composer install --no-interaction --prefer-dist || true
fi

exec "$@"
