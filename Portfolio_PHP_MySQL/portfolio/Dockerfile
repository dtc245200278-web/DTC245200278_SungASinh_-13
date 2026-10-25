
FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql

COPY php/security.ini /usr/local/etc/php/conf.d/security.ini

COPY . /var/www/html/
