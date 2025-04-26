#!/bin/bash

# Wait for database to be ready
if [ ! -z "$DB_HOST" ]; then
    echo "Waiting for database connection..."
    
    ATTEMPTS=0
    MAX_ATTEMPTS=30
    
    until mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; do
        ATTEMPTS=$((ATTEMPTS+1))
        if [ $ATTEMPTS -gt $MAX_ATTEMPTS ]; then
            echo "Error: Could not connect to database after $MAX_ATTEMPTS attempts."
            exit 1
        fi
        echo "Waiting for database connection... ($ATTEMPTS/$MAX_ATTEMPTS)"
        sleep 2
    done
    
    echo "Database connection established."
fi

# Run migrations if in development
if [ "$APP_ENV" = "local" ] || [ "$APP_ENV" = "development" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Start cron
service cron start

# Start supervisor (includes Apache)
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
