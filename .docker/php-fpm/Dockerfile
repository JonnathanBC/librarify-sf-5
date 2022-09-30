FROM php:8.1-fpm
ARG TIMEZONE

RUN usermod -u 1000 www-data

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && pecl install xdebug \
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache xdebug

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN git config --global user.email "jonnabcl56@gmail.com" \ 
    && git config --global user.name "JonnathanBC"

WORKDIR /var/www/librarify