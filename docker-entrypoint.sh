#!/bin/bash
set -e

# Ensure config.php exists
if [ ! -f /var/www/html/config.php ] && [ -f /var/www/html/config.php.example ]; then
    echo "Creating config.php from config.php.example..."
    cp /var/www/html/config.php.example /var/www/html/config.php
    chmod 644 /var/www/html/config.php
fi

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

# Execute the command passed as arguments
exec "$@"
