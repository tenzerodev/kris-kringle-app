FROM richarvey/nginx-php-fpm:3.1.6

COPY . /var/www/html

ENV SKIP_COMPOSER 1
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Allow composer to run
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Fix permissions
RUN chmod -R 777 /var/www/html/storage