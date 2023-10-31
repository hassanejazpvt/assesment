# Use the official PHP 8.1 Apache image
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# Install system dependencies, including freetype2
RUN apt-get update && apt-get install -y libjpeg62-turbo-dev libpng-dev libfreetype6-dev

# Configure and install GD extension
RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install gd

# Install Node.js and npm
COPY --from=node:18.16.0-slim /usr/local/bin /usr/local/bin
COPY --from=node:18.16.0-slim /usr/local/lib/node_modules/npm /usr/local/lib/node_modules/npm

# Install PHP extensions
RUN docker-php-ext-install pdo mysqli pdo_mysql zip
RUN docker-php-ext-enable mysqli

# Install Composer 2
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache modules
RUN a2enmod rewrite

# Copy your PHP application into the /var/www/html directory
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install Dependencies
RUN composer install --no-interaction
RUN npm i

# Expose port 80 for Apache
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
