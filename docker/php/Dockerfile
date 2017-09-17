FROM php:7.1-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev g++ \
    vim \
    supervisor

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# docker-php-ext-install is needed to install php ext in docker image
RUN docker-php-ext-configure intl
RUN docker-php-ext-install mbstring bcmath intl

RUN apt-get install -y libssl-dev

RUN pecl install mongodb && docker-php-ext-enable mongodb

COPY php.ini /usr/local/etc/php/

RUN usermod -u 1000 www-data

WORKDIR /var/www/devProTestTask

RUN apt-get install -y zlib1g-dev
RUN docker-php-ext-install zip