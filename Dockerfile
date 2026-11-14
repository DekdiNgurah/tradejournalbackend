
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    ca-certificates \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    intl \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel project
COPY . .

# Install Laravel dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Create storage directories and symbolic link
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/data \
    storage/app/public/trades \
    storage/logs \
    bootstrap/cache \
    && php artisan storage:link \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Configure Apache to serve Laravel public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" \
    /etc/apache2/sites-available/000-default.conf \
    && sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' \
    /etc/apache2/apache2.conf \
    && sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

EXPOSE 10000

CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]
