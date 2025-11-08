# Use an official PHP image with Apache
FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy your project files to the web directory
COPY . /var/www/html/

# Expose port 80 for web traffic
EXPOSE 80
