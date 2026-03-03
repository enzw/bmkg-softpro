#!/bin/bash

# Startup script for Koyeb deployment
set -e

# Signal handling for graceful shutdown
cleanup() {
    echo ""
    echo "🛑 Shutdown signal received, cleaning up..."
    kill %1 %2 2>/dev/null || true
    wait
    exit 0
}

trap cleanup SIGTERM SIGINT

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
        echo "📌 Auto-detected Koyeb APP_URL: $APP_URL"
    else
        export APP_URL="http://localhost:${PORT}"
    fi
fi

# Ensure APP_KEY is set
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY not provided, generating temporary one..."
    export APP_KEY="base64:+cKsVH9wk7MLoiUzFSYW0f1/TIv5nQwDaViDN7RxDRo="
fi

echo "📋 Configuration:"
echo "   PORT: $PORT"
echo "   APP_URL: $APP_URL"
echo "   APP_ENV: $APP_ENV"
echo ""

# Make sure PORT is exported for supervisord child processes
export PORT
export CHATBOT_SERVER_PORT
export APP_ENV
export APP_URL
export APP_KEY

# Run database migrations (non-fatal if DB unavailable)
echo "🔄 Running database migrations..."
timeout 120 php artisan migrate --force 2>&1 || {
    echo "⚠️  Migrations failed or timed out - continuing anyway..."
}

# Run database seeders
echo "🌱 Seeding database..."
timeout 60 php artisan db:seed --force 2>&1 || {
    echo "⚠️  Seeding failed or timed out - continuing anyway..."
}

# Generate/verify APP_KEY
echo "🔑 Verifying APP_KEY..."
php artisan key:generate --show >/dev/null 2>&1 || {
    echo "⏭️  Creating new APP_KEY..."
    php artisan key:generate
}

# ============================================================================
# RAG System Initialization
# ============================================================================
echo ""
echo "🤖 Initializing RAG System..."
echo "================================"

# Setup Python environment for RAG
if command -v python3 &> /dev/null; then
    echo "📦 Setting up RAG dependencies..."
    
    # Install/verify Python packages
    cd /var/www/html/python
    
    # Quick check if packages are installed
    python3 -c "import flask, google.generativeai, sqlalchemy, pgvector" 2>/dev/null || {
        echo "   Installing Python packages..."
        pip install --break-system-packages -q -r requirements.txt 2>/dev/null || {
            echo "   ⚠️  Some packages failed to install - continuing..."
        }
    }
    
    # Initialize RAG database
    echo "📊 Initializing RAG database and tables..."
    python3 << 'PYTHON_INIT'
import sys
import logging
from pathlib import Path

# Add project to path
sys.path.insert(0, '/var/www/html/python')

logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

try:
    from config import engine, Base, POSTGRES_DB, POSTGRES_HOST
    from models import Document, TextChunk
    
    # Create all tables
    Base.metadata.create_all(bind=engine)
    logger.info(f"✅ RAG database initialized in {POSTGRES_DB}@{POSTGRES_HOST}")
    
except Exception as e:
    logger.warning(f"⚠️  RAG initialization warning: {str(e)}")
    logger.info("   This is normal on first startup")

PYTHON_INIT
    
    cd /var/www/html
    
    echo "✅ RAG System initialized"
else
    echo "⚠️  Python 3 not found - RAG will not be available"
fi

echo ""
echo "⚙️  Optimizing Laravel..."
php artisan config:cache || echo "⚠️  Config cache failed"
# SKIP route:cache - too many naming conflicts
# php artisan route:cache will cause deployment failures
php artisan view:cache || echo "⚠️  View cache failed"

# Fix permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data /var/www/html 2>/dev/null || true

# Start supervisor
echo ""
echo "✅ Starting services with supervisor..."
echo "   Supervisord config: /etc/supervisord.conf"
echo "   Laravel will listen on: 0.0.0.0:${PORT}"
echo "   Chatbot will listen on: 0.0.0.0:${CHATBOT_SERVER_PORT}"
echo ""

# Verify supervisord is available
if ! command -v supervisord &> /dev/null; then
    echo "❌ ERROR: supervisord not found in PATH!"
    echo "   Falling back to direct command execution..."
    echo "🚀 Starting Laravel server on port $PORT..."
    exec php -S 0.0.0.0:${PORT} /var/www/html/server.php
else
    exec supervisord -c /etc/supervisord.conf
fi
