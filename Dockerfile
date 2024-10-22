FROM php:8.1-apache

COPY web /var/www/html/

WORKDIR /var/www/html/

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

EXPOSE 80