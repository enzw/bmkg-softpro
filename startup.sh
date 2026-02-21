#!/bin/bash
set -e

echo "======================================"
echo "🚀 Starting BMKG SoftPro Application"
echo "======================================"

# Environment setup
export PORT=${PORT:-8080}
export CHATBOT_SERVER_PORT=${CHATBOT_SERVER_PORT:-3001}
export APP_ENV=${APP_ENV:-production}

# Set APP_URL for Koyeb
if [ -z "$APP_URL" ]; then
    if [ ! -z "$KOYEB_APP_NAME" ] && [ ! -z "$KOYEB_SPACE_NAME" ]; then
        export APP_URL="https://${KOYEB_APP_NAME}-${KOYEB_SPACE_NAME}.koyeb.app"
    else
        export APP_URL="http://localhost:${PORT}"
    fi
fi

echo "📋 Configuration:"
echo "   PORT: $PORT"
echo "   APP_URL: $APP_URL"
echo "   APP_ENV: $APP_ENV"
echo ""

# Database migrations
echo "🔄 Running migrations..."
php artisan migrate --force 2>&1 || echo "⚠️  Migration skipped (DB unavailable)"

# Optimize
echo "⚙️  Optimizing..."
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true

# Permissions
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "✅ Startup complete! Services starting..."
echo ""

# Start chatbot in background
echo "🤖 Starting chatbot on port $CHATBOT_SERVER_PORT..."
cd /var/www/html
node chatbot-server.js > /var/log/chatbot.log 2>&1 &
CHATBOT_PID=$!
echo "   Chatbot PID: $CHATBOT_PID"

# Start Laravel server
echo "🌐 Starting Laravel on port $PORT..."
php -S 0.0.0.0:${PORT} -t public
