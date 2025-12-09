FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libzip-dev zip unzip curl libxml2-dev libpq-dev \
    libicu-dev \
    && docker-php-ext-install intl pdo pdo_mysql mbstring zip exif pcntl gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install NodeJS for Vite build
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www

# Copy composer files
COPY composer.json composer.lock ./



# Copy app files (including .env)
COPY . .

# Copy .env (if separate)
# COPY .env .env
# Install backend deps (vendor)
RUN composer install --no-dev --optimize-autoloader
RUN npm install
# Generate APP_KEY (NOW vendor exists)
RUN php artisan key:generate

# Cache config for performance
RUN php artisan config:cache

# Fix permissions
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Add entrypoint script
COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
