FROM php:8.1-apache

COPY web /var/www/html/

WORKDIR /var/www/html/

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN chmod 777 cache/

EXPOSE 80