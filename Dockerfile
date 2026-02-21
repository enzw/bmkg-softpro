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
# Stage 2 - Backend (Laravel + Node Chatbot)
# ======================
FROM php:8.2

# Install system deps + Node.js
RUN apt-get update && apt-get install -y \
    nodejs npm \
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

# Copy project files
COPY . .

# Copy Vite build result
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress

# Install Node dependencies for chatbot ONLY
RUN npm install @google/generative-ai express cors dotenv

# Permission
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose platform port
EXPOSE 8080

# Environment defaults
ENV PORT=8080 \
    APP_ENV=production \
    CHATBOT_SERVER_PORT=3001

# Start services
CMD set -e; \
    echo "🔄 Running migrations..."; \
    php artisan migrate --force || true; \
    echo "✅ Migrations completed"; \
    echo "🚀 Starting Node chatbot on ${CHATBOT_SERVER_PORT}..."; \
    node chatbot-server.js & \
    echo "🚀 Starting Laravel server on port ${PORT}..."; \
    php -S 0.0.0.0:${PORT} -t public