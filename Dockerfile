FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html/

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create config.php from example if it doesn't exist
# Strip Windows CRLF from all PHP files — heredoc EOF markers break with \r on Linux
RUN if [ ! -f config.php ] && [ -f config.php.example ]; then \
        cp config.php.example config.php && \
        chmod 644 config.php; \
    fi \
    && find /var/www/html -name '*.php' -exec sed -i 's/\r$//' {} +

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p temp attachments uploads \
    && chmod -R 777 temp attachments uploads

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache to use port 8080 (Render requirement)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Show PHP errors in browser and Apache log instead of blank 502
RUN echo "display_errors=On" > /usr/local/etc/php/conf.d/errors.ini \
    && echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/errors.ini \
    && echo "log_errors=On" >> /usr/local/etc/php/conf.d/errors.ini

# Copy entrypoint, fix Windows CRLF line endings, set executable
COPY docker-entrypoint.sh /usr/local/bin/
RUN sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port
EXPOSE 8080

# Use entrypoint script to ensure config.php exists at runtime
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
