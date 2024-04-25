# Use the official PHP image with Apache
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    libpq-dev \
    curl \
    unzip \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    mbstring

# Set the working directory in the container
WORKDIR /srv/app

# Copy the PHP configuration
COPY .docker/php/php.ini /usr/local/etc/php/php.ini

# Download and install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Symfony application files to the container
COPY . /srv/app

# Set Composer to allow running as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer dependencies
RUN composer install --prefer-dist --no-progress --no-interaction

# Apache configuration
# Configure Apache Document Root
ENV APACHE_DOCUMENT_ROOT /srv/app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
