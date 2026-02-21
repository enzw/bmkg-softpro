#!/bin/bash
set -e

# Startup script for Koyeb deployment

echo "======================================"
echo "🚀 Starting BMKG SoftPro Application"
echo "======================================"

# Environment
export PORT=${PORT:-8080}
export CHATBOT_SERVER_PORT=${CHATBOT_SERVER_PORT:-3001}
export APP_ENV=${APP_ENV:-production}

echo "📋 Configuration:"
echo "   - PORT: $PORT"
echo "   - CHATBOT_SERVER_PORT: $CHATBOT_SERVER_PORT"
echo "   - APP_ENV: $APP_ENV"

# Run database migrations
echo ""
echo "🔄 Running database migrations..."
php artisan migrate --force || {
    echo "⚠️  Migration failed or database not available"
}

# Optimize Laravel
echo "⚙️  Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data /var/www/html

# Start supervisor
echo ""
echo "✅ Starting services with supervisor..."
exec supervisord -c /etc/supervisord.conf
