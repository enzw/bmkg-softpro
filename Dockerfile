# ======================
# Stage 1 - Build Frontend (Vite)
# ======================
FROM node:18 AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


# ======================
# Stage 2 - Backend (Laravel)
# ======================
FROM php:8.2-fpm

# Install system & PHP extensions (FULL, biar Composer diem)
RUN apt-get update && apt-get install -y \
    git curl unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        zip \
        intl \
        gd \
        bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app source
COPY . .

# Copy Vite build result
COPY --from=frontend /app/public/build ./public/build

# Create dummy env for build
RUN echo "APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=" > .env

# Install PHP dependencies (anti memory crash)
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress

# Permission
RUN chown -R www-data:www-data storage bootstrap/cache

# Render default port
EXPOSE 10000

# Start PHP-FPM
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
