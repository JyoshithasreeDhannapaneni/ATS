#!/bin/bash
set -e

# Ensure config.php exists
if [ ! -f config.php ] && [ -f config.php.example ]; then
    echo "Creating config.php from config.php.example..."
    cp config.php.example config.php
    chmod 644 config.php
fi

# Execute the command passed as arguments
exec "$@"
