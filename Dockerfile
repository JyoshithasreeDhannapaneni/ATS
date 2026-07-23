FROM php:8.3-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    antiword \
    poppler-utils \
    html2text \
    unrtf \
    default-mysql-client \
    && docker-php-ext-install mysqli pdo_mysql mbstring exif pcntl bcmath gd zip pdo_pgsql pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

RUN composer install --no-dev --optimize-autoloader

RUN if [ ! -f config.php ] && [ -f config.php.example ]; then \
        cp config.php.example config.php && \
        chmod 644 config.php; \
    fi

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && mkdir -p temp attachments uploads \
    && chmod -R 755 temp attachments uploads

RUN a2enmod rewrite

RUN echo '<Directory /var/www/html>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/opencats.conf \
    && a2enconf opencats

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
