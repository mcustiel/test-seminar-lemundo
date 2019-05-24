FROM php:7.2-fpm

RUN apt-get update && apt-get -y install git gzip wget libgcrypt11-dev zlib1g-dev zip bzip2 libbz2-dev libmcrypt-dev sqlite3 libsqlite3-dev
RUN docker-php-ext-install zip bz2 pdo_sqlite

# Setup composer
RUN EXPECTED_SIGNATURE="$(wget -q -O - https://composer.github.io/installer.sig)" && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', 'composer-setup.php');")" && \
    if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ] ; then >&2 echo 'ERROR: Invalid installer signature'; rm composer-setup.php; exit 1; fi && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    RESULT=$? && \
    rm composer-setup.php

RUN chmod +x /usr/local/bin/composer

RUN pecl install xdebug-2.6.1
RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
	&& echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini && docker-php-ext-enable xdebug

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

EXPOSE 8080