# Use the official PHP 8.2 image with Apache
FROM php:8.4-apache

# 1. Install development packages and clean up apt cache
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    libzip-dev \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 3. Enable Apache mod_rewrite (needed for Laravel routes)
RUN a2enmod rewrite

# 4. Configure Apache DocumentRoot to point to /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 5. Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Set working directory
WORKDIR /var/www/html

# 7. Copy project files
COPY . /var/www/html

# 8. Install dependencies
RUN composer install --no-dev --optimize-autoloader

# 9. Fix permissions so Apache can read/write
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache