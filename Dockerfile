# Use the official PHP image with Apache
FROM php:8.1-apache

# Set the timezone
ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    libpq-dev \
    curl \
    unzip \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring

# Verify that the extensions are enabled
RUN php -m | grep pdo_pgsql && php -m | grep mbstring

# Set the working directory in the container
WORKDIR /srv/app

# Copy the PHP configuration
COPY .docker/php/php.ini /usr/local/etc/php/php.ini

# Download and install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Apache configuration
# Configure Apache Document Root
ENV APACHE_DOCUMENT_ROOT /srv/app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Expose port 80
EXPOSE 80

# Set Composer to allow running as root
ENV COMPOSER_ALLOW_SUPERUSER=1

ARG DATABASE_URL

# Copy the Symfony application files to the container
COPY . /srv/app

# Install Composer dependencies
# It's important that this step is after copying the application code and after the DATABASE_URL arg is declared
RUN composer install --prefer-dist --no-progress --no-interaction --no-scripts
