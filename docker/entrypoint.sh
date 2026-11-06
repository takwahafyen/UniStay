#!/bin/bash
set -e

echo "🚀 StudentHousing — Production Entrypoint"

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration for performance
echo "⚡ Caching configuration, routes and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force --no-interaction

# Seed database only if users table is empty (first deployment)
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "🌱 First deployment detected — seeding database..."
    php artisan db:seed --force --no-interaction
fi

# Create storage link
php artisan storage:link --force 2>/dev/null || true

echo "✅ Application ready! Starting Supervisor (Nginx + PHP-FPM)..."

# Execute the main CMD (supervisord)
exec "$@"
