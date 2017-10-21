FROM php:7.1-apache

# Configure Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

# Configure PHP
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ext-xdebug.ini /usr/local/etc/php/conf.d/ext-xdebug.ini

# Configure project
COPY . /var/www/html
