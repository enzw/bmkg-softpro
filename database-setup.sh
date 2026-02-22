#!/bin/bash

# Database setup script for Koyeb
# Run this manually after deployment to reset database if needed
# Command: php artisan tinker < setup-db.php
# Or in container: docker exec <container-id> bash database-setup.sh

set -e

echo "======================================"
echo "🔧 Database Setup Script"
echo "======================================"
echo ""

echo "🗑️  Dropping all tables..."
php artisan db:wipe --force

echo "🔄 Running database migrations..."
php artisan migrate --force

echo "🌱 Seeding database..."
php artisan db:seed --force

echo ""
echo "✅ Database setup complete!"
echo "   - All tables dropped and recreated"
echo "   - Migrations applied"
echo "   - Seeders executed"
