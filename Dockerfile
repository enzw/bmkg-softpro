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
FROM php:8.2-cli-alpine

# Install system deps + Node.js + Supervisor
RUN apk add --no-cache \
    nodejs npm \
    git curl unzip \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    zip \
    supervisor \
    bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        zip \
        intl \
        gd \
        bcmath \
        opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . .

# Copy Vite build result
COPY --from=frontend /app/public/build ./public/build

# Copy supervisor and entrypoint configs
COPY supervisord.conf /etc/supervisord.conf
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

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

# Create log directories
RUN mkdir -p /var/log && \
    touch /var/log/laravel.log /var/log/chatbot.log /var/log/supervisord.log && \
    chown -R www-data:www-data /var/log

# Expose platform port
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=5 \
    CMD curl -sf http://localhost:8080/health || exit 1

# Environment defaults
ENV PORT=8080 \
    APP_ENV=production \
    CHATBOT_SERVER_PORT=3001 \
    PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_ENABLE_CLI=1

# Start services via entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Fallback command (if entrypoint fails)
CMD ["supervisord", "-c", "/etc/supervisord.conf"]