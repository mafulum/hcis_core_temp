FROM php:5.6-apache

RUN apt-get -y update && apt-get upgrade -y
RUN apt-get -y install --fix-missing apt-utils nano wget dialog \
    build-essential git curl libcurl3 libcurl3-dev zip \
    libmcrypt-dev libsqlite3-dev libsqlite3-0 mysql-client \
    zlib1g-dev libicu-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip syslog-ng libpq-dev \
    git curl \
    lua-zlib-dev \
    libmemcached-dev \
    syslog-ng libpq-dev build-essential libssl-dev zlib1g-dev libjpeg-dev libgmp-dev libicu-dev freetds-dev libaspell-dev libsnmp-dev libtidy-dev libxslt-dev libzip-dev libonig-dev libbz2-dev libmcrypt-dev libxslt-dev curl libcurl4-openssl-dev libedit-dev apt-utils libxml2-dev

RUN docker-php-ext-configure opcache --enable-opcache
RUN apt-get install libgmp3-dev
RUN docker-php-ext-install curl tokenizer mcrypt mysqli json pdo pdo_mysql pgsql pdo_pgsql mbstring zip exif pcntl xsl bcmath gmp bz2 calendar ctype curl ftp phar posix session simplexml snmp soap sockets sysvmsg sysvsem sysvshm tidy xml
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/apache2.conf /etc/apache2/apache2.conf
COPY docker/php.ini /usr/local/etc/php/php.ini
COPY docker/php.ini-development /usr/local/etc/php/php.ini-development
COPY docker/php.ini-production /usr/local/etc/php/php.ini-production
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html/
ADD . /var/www/html
RUN a2enmod rewrite