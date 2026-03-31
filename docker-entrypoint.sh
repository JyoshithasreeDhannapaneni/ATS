#!/bin/bash
set -e

echo "========================================="
echo "  OpenCATS ATS — Container Starting"
echo "========================================="

# Apache listens on port 80 by default inside the container.
# For cloud hosts that inject $PORT (e.g. Render), override Apache's listen port.
if [ -n "$PORT" ] && [ "$PORT" != "80" ]; then
    echo "Overriding Apache port to $PORT"
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-available/000-default.conf
fi

# Ensure config.php exists (it is excluded from the build by .dockerignore)
if [ ! -f /var/www/html/config.php ] && [ -f /var/www/html/config.php.example ]; then
    echo "Creating config.php from config.php.example..."
    cp /var/www/html/config.php.example /var/www/html/config.php
    chmod 644 /var/www/html/config.php
    chown www-data:www-data /var/www/html/config.php
fi

# Ensure runtime directories exist and are writable
mkdir -p /var/www/html/temp /var/www/html/attachments /var/www/html/uploads
chown -R www-data:www-data /var/www/html/temp /var/www/html/attachments /var/www/html/uploads
chmod -R 777 /var/www/html/temp /var/www/html/attachments /var/www/html/uploads

# Log database configuration
echo "--- Database Configuration ---"
echo "  HOST: ${DATABASE_HOST:-NOT SET}"
echo "  PORT: ${DATABASE_PORT:-3306}"
echo "  USER: ${DATABASE_USER:-NOT SET}"
echo "  NAME: ${DATABASE_NAME:-NOT SET}"

# Wait for MySQL to be reachable (up to 60 seconds)
if [ -n "$DATABASE_HOST" ]; then
    echo "--- Waiting for MySQL at $DATABASE_HOST:${DATABASE_PORT:-3306} ---"
    for i in $(seq 1 30); do
        if php -r "
            \$c = @new mysqli('${DATABASE_HOST}', '${DATABASE_USER}', '${DATABASE_PASS}', '', (int)'${DATABASE_PORT:-3306}');
            if (\$c->connect_error) exit(1);
            \$c->close(); exit(0);
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
