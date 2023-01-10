FROM php:8.2-fpm

# Node version

ARG NODE_VERSION=19

# Default user

ARG LINUX_USER_ID=1000
ARG LINUX_USER=blackfur
ARG LINUX_PASSWORD=12345678

# Dependencies

RUN apt-get update && apt-get install -y \
	sudo \
	nano \
	unzip \
	ca-certificates \
	libzip-dev \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
	libwebp-dev \
	libonig-dev \
	libxml2-dev

# PHP extensions

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-install zip mbstring xml pdo_mysql exif \
	&& pecl install redis \
	&& docker-php-ext-enable redis

# Composer

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Node

RUN curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
	&& apt-get install -y nodejs \
	&& npm install -g npm

# Delete temp data

RUN apt-get -y autoremove \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Add default user

RUN useradd -ms /bin/bash --no-user-group -g www-data -G sudo -u $LINUX_USER_ID $LINUX_USER

# Set user password

RUN echo "root:$LINUX_PASSWORD"|chpasswd && echo "$LINUX_USER:$LINUX_PASSWORD"|chpasswd