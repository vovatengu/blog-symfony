FROM ubuntu:24.04

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
    software-properties-common && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update && apt-get install -y --no-install-recommends \
    php8.3-fpm php8.3-cli php8.3-mysql php8.3-intl php8.3-gd \
    php8.3-zip php8.3-soap php8.3-bcmath php8.3-xml php8.3-mbstring \
    php8.3-curl php8.3-opcache \
    nginx \
    phpmyadmin \
    wkhtmltopdf \
    ca-certificates curl git unzip nodejs npm zsh \
    && rm -rf /var/lib/apt/lists/*

RUN sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" "" --unattended

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -L https://cs.symfony.com/download/php-cs-fixer-v3.phar -o /usr/local/bin/php-cs-fixer \
    && chmod +x /usr/local/bin/php-cs-fixer

# Custom PHP config
COPY config/docker/php/custom.ini /etc/php/8.3/fpm/conf.d/99-custom.ini
COPY config/docker/php/custom.ini /etc/php/8.3/cli/conf.d/99-custom.ini

# Nginx: disable default site, add app and pma vhosts
RUN rm -f /etc/nginx/sites-enabled/default
COPY config/nginx/local.conf /etc/nginx/conf.d/cv.conf
COPY config/nginx/pma_local.conf /etc/nginx/conf.d/pma.conf

# phpMyAdmin: DB host override
COPY config/phpmyadmin/config.php /etc/phpmyadmin/conf.d/99-docker.php

# Trust the self-signed dev cert
COPY config/ssl/localhost.crt /usr/local/share/ca-certificates/docker-dev.crt
RUN update-ca-certificates

# Project dir owned by www-data
RUN mkdir -p /www/sftest2 && chown www-data:www-data /www/sftest2

WORKDIR /www/sftest2

# Ensure correct ownership on Linux (no-op on Mac Docker Desktop)
RUN chown -R www-data:www-data /www/sftest2

CMD service php8.3-fpm start && service nginx start && tail -f /dev/null
