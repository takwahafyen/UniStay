#!/bin/bash
set -e

echo "🚀 StudentHousing — Production Entrypoint"

# Wait for database connection if configured
if [ -n "$DATABASE_URL" ] || [ -n "$DB_HOST" ]; then
    echo "⏳ Waiting for database connection..."
    for i in {1..30}; do
        if php artisan db:monitor 2>/dev/null || php -r "try { new PDO(env('DB_CONNECTION') === 'pgsql' ? 'pgsql:host='.env('DB_HOST').';dbname='.env('DB_DATABASE') : 'mysql:host='.env('DB_HOST').';dbname='.env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }"; then
            echo "✅ Database is up and reachable!"
            break
        fi
        echo "   Database not ready yet... waiting (attempt $i/30)"
        sleep 2
    done
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration for performance
echo "⚡ Optimizing Laravel caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force --no-interaction || echo "⚠️ Migration notice: Check DB connection"

# Seed database only if users table is empty (first deployment)
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "🌱 First deployment detected — seeding sample data..."
    php artisan db:seed --force --no-interaction || true
fi

# Create storage link
php artisan storage:link --force 2>/dev/null || true

echo "✅ Application ready! Starting Supervisor (Nginx + PHP-FPM)..."

# Execute the main CMD (supervisord)
exec "$@"
