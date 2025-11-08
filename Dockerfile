# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install required extensions for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy all files to the web directory
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
