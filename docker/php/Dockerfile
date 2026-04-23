FROM ubuntu:22.04

LABEL maintainer="TaskFlow"

ARG NODE_VERSION=20

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    gnupg \
    git \
    unzip \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP 8.4
RUN apt-get update \
    && apt-get install -y software-properties-common \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update \
    && apt-get install -y \
        php8.4-fpm \
        php8.4-cli \
        php8.4-mysql \
        php8.4-gd \
        php8.4-curl \
        php8.4-mbstring \
        php8.4-xml \
        php8.4-zip \
        php8.4-bcmath \
        php8.4-intl \
        php8.4-readline \
        php8.4-redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# php-fpm: TCP 9000 вместо unix socket
RUN mkdir -p /run/php \
    && sed -i "s/listen = \/run\/php\/php8.4-fpm.sock/listen = 9000/" /etc/php/8.4/fpm/pool.d/www.conf \
    && sed -i "s/;listen.owner = www-data/listen.owner = www-data/" /etc/php/8.4/fpm/pool.d/www.conf \
    && sed -i "s/;listen.group = www-data/listen.group = www-data/" /etc/php/8.4/fpm/pool.d/www.conf

COPY docker/php/php.ini /etc/php/8.4/fpm/conf.d/99-taskflow.ini
COPY docker/php/php.ini /etc/php/8.4/cli/conf.d/99-taskflow.ini
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
