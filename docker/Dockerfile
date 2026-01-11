FROM php:8.3-apache

# System packages needed for Composer and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
 && docker-php-ext-install zip pdo_mysql \
 && rm -rf /var/lib/apt/lists/*

# Enable required Apache modules
RUN a2enmod rewrite headers

# Set correct DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public_html

# Update Apache configs to use public_html
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Allow .htaccess overrides
RUN sed -ri 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

# Dev-friendly PHP settings
RUN { \
  echo "display_errors=On"; \
  echo "error_reporting=E_ALL"; \
} > /usr/local/etc/php/conf.d/dev.ini