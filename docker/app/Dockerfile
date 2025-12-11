FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libzip-dev zip unzip curl git libxml2-dev libpq-dev libicu-dev \
    && docker-php-ext-install intl pdo pdo_mysql mbstring zip exif pcntl gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (for Vite build)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www

# Copy dependency files first (better caching)
COPY composer.json composer.lock package.json package-lock.json vite.config.js ./

# Install PHP dependencies without scripts (helpers.php may not exist yet)
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Install JS dependencies
RUN npm install

# Now copy full Laravel project
COPY . .

# Now generate optimized autoloader (helpers.php exists)
RUN composer dump-autoload --optimize

# Build Vite assets
RUN npm run build

# Cache Laravel config & routes
RUN php artisan config:cache && php artisan route:cache

# Permissions
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
