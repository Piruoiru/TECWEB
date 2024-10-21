FROM php:8.1

COPY web /var/www/html/

WORKDIR /var/www/html/

EXPOSE 80

CMD ["php","-S","0.0.0.0:80"]