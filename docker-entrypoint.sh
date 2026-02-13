#!/bin/bash
set -e

# Ensure config.php exists
if [ ! -f /var/www/html/config.php ] && [ -f /var/www/html/config.php.example ]; then
    echo "Creating config.php from config.php.example..."
    cp /var/www/html/config.php.example /var/www/html/config.php
    chmod 644 /var/www/html/config.php
fi

# Log which DB host will be used (env var or fallback)
if [ -n "$DATABASE_HOST" ]; then
    echo "DATABASE_HOST env var is set: $DATABASE_HOST"
else
    echo "WARNING: DATABASE_HOST env var is NOT set. Using fallback from config.php."
fi

# Execute the command passed as arguments
exec "$@"
