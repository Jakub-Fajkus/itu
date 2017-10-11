FROM php:7.1-apache

MAINTAINER Jakub Fajkus <jakub.fajkus@gmail.com>

COPY . /var/www/html/


RUN docker-php-ext-install pcntl iconv
RUN apt-get update && apt-get install -y libmemcached-dev zlib1g-dev

RUN docker-php-ext-configure bcmath \
    && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer \
    && echo "memory_limit = 2048M" >> /usr/local/etc/php/php.ini

RUN apt-get update\
    && apt-get -y upgrade \
    && apt-get install -y nano \
    && apt-get install -y npm \
    && curl -sL https://deb.nodesource.com/setup_8.x |  bash - \
    && apt-get install -y nodejs

#    && npm install npm@latest -g \
#    && rm /usr/local/bin/npm \
#    && rm /usr/bin/npm \
#    && ln -s /usr/local/lib/node_modules/npm /usr/bin/npm


RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.profiler_enable=0" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.profiler_output_dir=/var/app/var/xdebug/" >> /usr/local/etc/php/php.ini \
    && echo "xdebug.profiler_enable_trigger=1" >> /usr/local/etc/php/php.ini

EXPOSE 80

#ENTRYPOINT ["sh", "docker/entrypoint.sh"]