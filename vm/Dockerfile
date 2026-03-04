# Offical Docker File for PHP
# https://hub.docker.com/_/php
FROM php:8.4-apache

# Set Current Directory
WORKDIR /var/www/html/

# Install dependencies
RUN apt-get clean && apt-get update && apt-get upgrade -y && apt-get install -y \
	rsync \
    git \
	npm \
	git \
	zip \
	curl \
	sudo \
	nano \
    unzip \
    zip \
	libpq-dev \
	locate \
	g++ \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libgd-dev \
    libonig-dev \
    libmagickwand-dev \
    libxml2-dev \
    libzip-dev \
	libicu-dev \
	libbz2-dev \
	libpng-dev \
	libjpeg-dev \
	libmcrypt-dev \
	libreadline-dev \
	libfreetype6-dev \
	libmcrypt-dev \
	libreadline-dev \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libwebp-dev \
    libmcrypt-dev \
	mariadb-client

RUN apt-get remove -y nodejs npm

RUN docker-php-source extract && \
    pecl install xdebug && \
    pecl install imagick && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-enable imagick && \
    docker-php-source delete

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install pcntl;

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-webp=/usr/include/  --with-jpeg=/usr/include/

# PHP Extensions
RUN docker-php-ext-install \
    gd \
    mbstring \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    fileinfo \
    exif \
    xml \
	zip

# Install Composer from Official Docker File
# https://hub.docker.com/_/composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Node
ENV NODE_VERSION=22.13.1
ENV NVM_DIR=/usr/local/nvm
RUN mkdir -p $NVM_DIR
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash \
  && . $NVM_DIR/nvm.sh \
  && nvm install $NODE_VERSION \
  && nvm alias default $NODE_VERSION \
  && nvm use default

ENV NODE_PATH $NVM_DIR/versions/node/v$NODE_VERSION/lib/node_modules
ENV PATH      $NVM_DIR/versions/node/v$NODE_VERSION/bin:/var/www/html/vendor/bin:$PATH
RUN echo "export NVM_DIR=\"/usr/local/nvm\"" >> ~/.bashrc \
    && echo "[ -s \"\$NVM_DIR/nvm.sh\" ] && \\. \"\$NVM_DIR/nvm.sh\"" >> ~/.bashrc \
    && echo "[ -s \"\$NVM_DIR/bash_completion\" ] && \\. \"\$NVM_DIR/bash_completion\"" >> ~/.bashrc
#
RUN #node --version
RUN #npm --version


# DocumentRoot to /public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Apache Server Name
RUN echo "ServerName snappy.local" | sudo tee /etc/apache2/conf-available/servername.conf
RUN sudo a2enconf servername

# Mod Rewrite
RUN a2enmod rewrite;

RUN updatedb
