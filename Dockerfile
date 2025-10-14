# ใช้ PHP 8.2 CLI
FROM php:8.2-cli

# Set working directory
WORKDIR /var/www/html

# ติดตั้ง dependencies ที่จำเป็น
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo pdo_mysql zip mbstring bcmath

# ติดตั้ง Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# ตั้ง permission ให้ storage และ vendor
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor || true \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor || true

# ติดตั้ง dependencies Laravel แบบ verbose
RUN composer install --no-dev --optimize-autoloader -vvv

# เปิด port สำหรับ Laravel Serve
EXPOSE 8080

# Command รัน Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
