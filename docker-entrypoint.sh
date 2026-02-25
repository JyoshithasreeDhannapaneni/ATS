#!/bin/bash
set -e

# Ensure config.php exists
if [ ! -f /var/www/html/config.php ] && [ -f /var/www/html/config.php.example ]; then
    echo "Creating config.php from config.php.example..."
    cp /var/www/html/config.php.example /var/www/html/config.php
    chmod 644 /var/www/html/config.php
fi

# Enable PHP error display so errors show in browser instead of blank 502
echo "display_errors = On"          >  /usr/local/etc/php/conf.d/zzz-errors.ini
echo "error_reporting = E_ALL"      >> /usr/local/etc/php/conf.d/zzz-errors.ini
echo "log_errors = On"              >> /usr/local/etc/php/conf.d/zzz-errors.ini
echo "error_log = /dev/stderr"      >> /usr/local/etc/php/conf.d/zzz-errors.ini

# Log database configuration (env vars)
echo "=== Database Configuration ==="
if [ -n "$DATABASE_HOST" ]; then
    echo "DATABASE_HOST: $DATABASE_HOST"
else
    echo "WARNING: DATABASE_HOST env var is NOT set!"
fi
echo "DATABASE_PORT: ${DATABASE_PORT:-not set}"
echo "DATABASE_USER: ${DATABASE_USER:-not set}"
echo "DATABASE_NAME: ${DATABASE_NAME:-not set}"
echo "DATABASE_SSL:  ${DATABASE_SSL:-not set}"

# Test DNS resolution of the database host
if [ -n "$DATABASE_HOST" ]; then
    echo "--- Testing DNS resolution for $DATABASE_HOST ---"
    if getent hosts "$DATABASE_HOST" > /dev/null 2>&1; then
        echo "DNS OK: $(getent hosts $DATABASE_HOST)"
    else
        echo "ERROR: Cannot resolve hostname '$DATABASE_HOST'"
        echo "Possible causes:"
        echo "  1. Aiven free-tier service has hibernated — go to console.aiven.io and power it on"
        echo "  2. Hostname is incorrect — double-check the value in Render Environment Variables"
        echo "  3. DNS propagation delay — wait a few minutes and redeploy"
    fi
fi
echo "=============================="

# Quick PHP + DB connectivity test before starting Apache
echo "=== PHP Startup Check ==="
php -r "echo 'PHP ' . phpversion() . ' OK' . PHP_EOL;" 2>&1 || echo "ERROR: PHP failed"
echo "Checking config.php syntax..."
php -l /var/www/html/config.php 2>&1 || echo "ERROR: config.php has syntax error"
echo "Checking index.php syntax..."
php -l /var/www/html/index.php 2>&1 || echo "ERROR: index.php has syntax error"
echo "Testing DB connection..."
php -r "
    require '/var/www/html/config.php';
    \$m = @mysqli_init();
    mysqli_ssl_set(\$m, null, null, null, null, null);
    \$ok = @mysqli_real_connect(\$m, DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME, (int)DATABASE_PORT, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
    echo \$ok ? 'DB connection OK' : 'DB connection FAILED: ' . mysqli_connect_error();
    echo PHP_EOL;
" 2>&1
echo "==========================="

# Execute the command passed as arguments
exec "$@"
