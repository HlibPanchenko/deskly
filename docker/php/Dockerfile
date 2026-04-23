FROM ubuntu:22.04

LABEL maintainer="TaskFlow"

ARG WWWGROUP=1000
ARG NODE_VERSION=20

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    gnupg \
    gosu \
    git \
    supervisor \
    sqlite3 \
    libcap2-bin \
    libpng-dev \
    python3 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP 8.2
RUN apt-get update \
    && apt-get install -y software-properties-common \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update \
    && apt-get install -y \
        php8.2-fpm \
        php8.2-cli \
        php8.2-mysql \
        php8.2-pgsql \
        php8.2-sqlite3 \
        php8.2-gd \
        php8.2-curl \
        php8.2-imap \
        php8.2-mbstring \
        php8.2-xml \
        php8.2-zip \
        php8.2-bcmath \
        php8.2-soap \
        php8.2-intl \
        php8.2-readline \
        php8.2-ldap \
        php8.2-msgpack \
        php8.2-igbinary \
        php8.2-redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# php-fpm: слушать на TCP 9000 вместо unix socket
RUN sed -i "s/listen = \/run\/php\/php8.2-fpm.sock/listen = 9000/" /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i "s/;listen.owner = www-data/listen.owner = www-data/" /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i "s/;listen.group = www-data/listen.group = www-data/" /etc/php/8.2/fpm/pool.d/www.conf

COPY docker/php/php.ini /etc/php/8.2/fpm/conf.d/99-taskflow.ini
COPY docker/php/php.ini /etc/php/8.2/cli/conf.d/99-taskflow.ini
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php/start-container /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container

EXPOSE 9000

ENTRYPOINT ["start-container"]
