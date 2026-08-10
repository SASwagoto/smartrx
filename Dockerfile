# PHP 8.3 Alpine image
FROM php:8.3-fpm-alpine

# Install necessary system dependencies
RUN apk add --no-cache \
    nginx \
    shadow \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    curl \
    bash

# Install PHP extensions and Redis support
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip bcmath opcache \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install Composer dependencies (Production Mode)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Copy Nginx configuration and start script
COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY .docker/start.sh /usr/local/bin/start.sh

# Set permissions
RUN chmod +x /usr/local/bin/start.sh \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port (Render uses dynamic PORT, but 80 is standard default)
EXPOSE 80

CMD ["/usr/local/bin/start.sh"]