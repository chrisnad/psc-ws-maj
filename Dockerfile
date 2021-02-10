FROM php:8.0.1-apache-buster

# app version, set with docker build --build-arg version=0.0.2
ARG version=0.0.4

RUN apt-get update

# 1. development packages
RUN apt-get install -y \
    git \
    zip \
    pkg-config \
    libcurl4-openssl-dev \
    curl \
    wget \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++ \
   && apt-get clean \
   && rm -rf /var/lib/apt/lists/*

ENV NODE_VERSION 15.6.0
ENV NVM_DIR /usr/local/nvm
RUN mkdir -p $NVM_DIR && curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash

# install node and npm
RUN . $NVM_DIR/nvm.sh \
     && nvm install $NODE_VERSION \
     && nvm alias default $NODE_VERSION \
     && nvm use default

# add node and npm to path so the commands are available
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH
# confirm installation
RUN node -v
RUN npm -v
# 2. apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -i 's/Require local/#Require local/g' /etc/apache2/mods-available/status.conf

# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN docker-php-ext-install \
    pdo_mysql \
    exif \
    sockets
# 4.1 install a third-party extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && echo "extension=mongodb.so" >> "$PHP_INI_DIR/php.ini"

# 5. composer
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# so when we execute CLI commands, all the host file's ownership remains intact
# otherwise command from inside container will create root-owned files and directories
ARG uid
RUN useradd -G www-data,root -u 1000 -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

RUN cd /var/www/html && wget https://github.com/prosanteconnect/psc-ws-maj/archive/v$version.tar.gz && \
    tar -xzf v$version.tar.gz --strip 1 && rm v$version.tar.gz

# Setup working directory
WORKDIR /var/www/html

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install

RUN php artisan cache:clear
RUN composer dump-autoload

# Npm run
RUN npm run production
