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
# Stage 2 - Backend (Laravel HTTP)
# ======================
FROM php:8.2

# Install system & PHP extensions
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

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy backend
COPY . .

# Copy Vite build
COPY --from=frontend /app/public/build ./public/build

# Dummy env (build only)
RUN echo "APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=" > .env

# Install deps
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress

# Permission
RUN chown -R www-data:www-data storage bootstrap/cache

# IMPORTANT: expose platform port
EXPOSE 8080

# Start Laravel HTTP server
CMD if [ "$RESET_DB" = "true" ]; then \
      php artisan migrate:fresh --force --seed; \
    else \
      php artisan migrate --force; \
    fi \
 && php artisan serve --host=0.0.0.0 --port=${PORT}