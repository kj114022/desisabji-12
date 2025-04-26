# Use PHP 8.2 with Apache as the base image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Set environment variables
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1 \
    DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zip \
    unzip \
    nodejs \
    npm \
    default-mysql-client \
    cron \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install and configure PHP extensions with better performance options
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    zip \
    intl \
    opcache \
    gd

# Configure opcache for production performance
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Configure PHP settings for Laravel
RUN { \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    echo 'memory_limit=512M'; \
    } > /usr/local/etc/php/conf.d/laravel-recommended.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create directory for custom configurations
RUN mkdir -p docker

# Configure Apache with security headers and performance optimizations
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks MultiViews\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
\n\
    # Enable gzip compression\n\
    <IfModule mod_deflate.c>\n\
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json\n\
    </IfModule>\n\
\n\
    # Security headers\n\
    Header always set X-Content-Type-Options "nosniff"\n\
    Header always set X-XSS-Protection "1; mode=block"\n\
    Header always set X-Frame-Options "SAMEORIGIN"\n\
    Header always set Referrer-Policy "strict-origin-when-cross-origin"\n\
\n\
    # Cache control for assets\n\
    <FilesMatch "\.(ico|pdf|jpg|jpeg|png|webp|js|css)$">\n\
        Header set Cache-Control "max-age=86400, public"\n\
    </FilesMatch>\n\
\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Enable Apache modules
RUN a2enmod rewrite headers deflate expires

# Create supervisor configuration for queue worker
RUN mkdir -p /etc/supervisor/conf.d/
RUN echo '[supervisord]\n\
nodaemon=true\n\
user=root\n\
logfile=/var/log/supervisor/supervisord.log\n\
pidfile=/var/run/supervisord.pid\n\
\n\
[program:apache2]\n\
command=apache2-foreground\n\
stdout_logfile=/dev/stdout\n\
stdout_logfile_maxbytes=0\n\
stderr_logfile=/dev/stderr\n\
stderr_logfile_maxbytes=0\n\
\n\
[program:laravel-worker]\n\
process_name=%(program_name)s_%(process_num)02d\n\
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600\n\
autostart=true\n\
autorestart=true\n\
stopasgroup=true\n\
killasgroup=true\n\
user=www-data\n\
numprocs=2\n\
redirect_stderr=true\n\
stdout_logfile=/var/www/html/storage/logs/worker.log\n\
stopwaitsecs=3600' > /etc/supervisor/conf.d/supervisord.conf

# Create startup script
RUN echo '#!/bin/bash\n\
\n\
# Wait for database to be ready\n\
if [ ! -z "$DB_HOST" ]; then\n\
    echo "Waiting for database connection..."\n\
    \n\
    ATTEMPTS=0\n\
    MAX_ATTEMPTS=30\n\
    \n\
    until mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1" >/dev/null 2>&1; do\n\
        ATTEMPTS=$((ATTEMPTS+1))\n\
        if [ $ATTEMPTS -gt $MAX_ATTEMPTS ]; then\n\
            echo "Error: Could not connect to database after $MAX_ATTEMPTS attempts."\n\
            exit 1\n\
        fi\n\
        echo "Waiting for database connection... ($ATTEMPTS/$MAX_ATTEMPTS)"\n\
        sleep 2\n\
    done\n\
    \n\
    echo "Database connection established."\n\
fi\n\
\n\
# Run migrations in non-production environments\n\
if [ "$APP_ENV" != "production" ]; then\n\
    echo "Running migrations..."\n\
    php artisan migrate --force\n\
fi\n\
\n\
# Clear cache\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Start cron for Laravel scheduler\n\
service cron start\n\
echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1" | crontab -\n\
\n\
# Start supervisor (handles Apache and queue workers)\n\
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf\n\
' > /usr/local/bin/start-container.sh

# Make the startup script executable
RUN chmod +x /usr/local/bin/start-container.sh

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies without running scripts
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

# Copy package.json for Node.js dependencies
COPY package.json package-lock.json vite.config.js ./

# Install Node.js dependencies
RUN npm ci

# Copy application files
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Build frontend assets
RUN npm run build

# Set proper permissions
RUN mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    bootstrap/cache \
    && chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    && chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Create symlink for storage
RUN php artisan storage:link || true

# Expose port 80
EXPOSE 80

# Start services using the startup script
CMD ["/usr/local/bin/start-container.sh"]