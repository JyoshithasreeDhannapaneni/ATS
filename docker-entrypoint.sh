#!/bin/bash
set -e
echo "========================================="
echo "  OpenCATS ATS — Container Starting"
echo "========================================="

if [ -n "$PORT" ] && [ "$PORT" != "80" ]; then
    echo "Overriding Apache port to $PORT"
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-available/000-default.conf
fi

if [ ! -f /var/www/html/config.php ] && [ -f /var/www/html/config.php.example ]; then
    echo "Creating config.php from config.php.example..."
    cp /var/www/html/config.php.example /var/www/html/config.php
    chmod 644 /var/www/html/config.php
    chown www-data:www-data /var/www/html/config.php
fi

mkdir -p /var/www/html/temp /var/www/html/attachments /var/www/html/uploads
chown -R www-data:www-data /var/www/html/temp /var/www/html/attachments /var/www/html/uploads
chmod -R 777 /var/www/html/temp /var/www/html/attachments /var/www/html/uploads

echo "--- Database Configuration ---"
echo "  HOST: ${DATABASE_HOST:-NOT SET}"
echo "  PORT: ${DATABASE_PORT:-3306}"
echo "  USER: ${DATABASE_USER:-NOT SET}"
echo "  NAME: ${DATABASE_NAME:-NOT SET}"

if [ -n "$DATABASE_HOST" ]; then
    echo "--- Waiting for MySQL at $DATABASE_HOST:${DATABASE_PORT:-3306} ---"
    for i in $(seq 1 30); do
        if php -r "
            try {
                \$dsn = 'mysql:host=${DATABASE_HOST};port=${DATABASE_PORT:-3306};dbname=${DATABASE_NAME}';
                \$pdo = new PDO(\$dsn, '${DATABASE_USER}', '${DATABASE_PASS}');
                \$pdo = null;
                exit(0);
            } catch (Exception \$e) { exit(1); }
        " 2>/dev/null; then
            echo "  MySQL connection OK"
            break
        fi
        echo "  Attempt $i/30 — waiting..."
        sleep 2
    done
fi

echo "========================================="
echo "  Ready — starting Apache"
echo "========================================="
exec "$@"
