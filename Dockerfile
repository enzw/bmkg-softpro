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

# Install system deps
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy app source
COPY . .

# Copy built frontend
COPY --from=frontend /app/public/build ./public/build

# Fake env for build (AMAN)
RUN cp .env.example .env

# Install PHP deps (anti memory crash)
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader

# Permission
RUN chown -R www-data:www-data storage bootstrap/cache

# Render expects port 10000
EXPOSE 10000

# Start PHP-FPM
CMD ["php-fpm"]
