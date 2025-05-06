# Use the official PHP image with FPM
FROM php:8.2-fpm
RUN apt-get update -y && apt-get install -y openssl zip unzip git
COPY installer .
RUN php installer --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql
ENV APP_DIR=/app
WORKDIR $APP_DIR
COPY . $APP_DIR
COPY .env $APP_DIR/.env 
RUN composer install
EXPOSE 8181
CMD php artisan key:generate && php artisan optimize:clear && php artisan migrate  && composer dumpautoload && php artisan serve  --host=0.0.0.0 --port=8181
