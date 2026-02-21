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

# Set APP_URL for Koyeb deployment
# If running on Koyeb, generate URL from environment
if [ -z "$APP_URL" ]; then
    if [ ! -z "$KOYEB_APP_NAME" ] && [ ! -z "$KOYEB_SPACE_NAME" ]; then
        export APP_URL="https://${KOYEB_APP_NAME}-${KOYEB_SPACE_NAME}.koyeb.app"
    else
        export APP_URL="http://localhost:${PORT}"
    fi
fi

# Ensure APP_KEY is set (required by Laravel)
if [ -z "$APP_KEY" ]; then
    echo "⚠️  WARNING: APP_KEY not set! Generate with: php artisan key:generate"
    export APP_KEY="base64:+cKsVH9wk7MLoiUzFSYW0f1/TIv5nQwDaViDN7RxDRo="
fi

echo "📋 Configuration:"
echo "   - PORT: $PORT"
echo "   - APP_URL: $APP_URL"
echo "   - CHATBOT_SERVER_PORT: $CHATBOT_SERVER_PORT"
echo "   - APP_ENV: $APP_ENV"

# Run database migrations
echo ""
echo "🔄 Running database migrations..."
php artisan migrate --force || {
    echo "⚠️  Migration failed or database not available"
}

# Generate app key if not exists
if php artisan key:generate --show 2>/dev/null | grep -q "base64:"; then
    echo "✅ APP_KEY already set"
else
    echo "🔑 Generating new APP_KEY..."
    php artisan key:generate
fi

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
