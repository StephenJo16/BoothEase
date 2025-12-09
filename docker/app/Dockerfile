FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libzip-dev zip unzip curl libxml2-dev libpq-dev \
    libicu-dev git \
    && docker-php-ext-install intl pdo pdo_mysql mbstring zip exif pcntl gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install NodeJS (for build only)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www

# Copy dependency files first
COPY composer.json composer.lock package.json package-lock.json vite.config.js ./

# ----------------- FIX START -----------------
# 1. Install dependencies but SKIP autoloader generation and scripts
#    (This prevents it from crashing looking for helpers.php)
RUN composer install --no-dev --no-scripts --no-autoloader

# 2. Install NPM dependencies
RUN npm install

# 3. Copy full application (including app/Helpers/helpers.php)
COPY . .

# 4. NOW generate the optimized autoloader since the file exists
RUN composer dump-autoload --optimize
# ----------------- FIX END -------------------

# Build Vite assets
RUN npm run build

# Fix permissions
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
