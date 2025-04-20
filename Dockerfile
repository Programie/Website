FROM node:18 AS webpack

WORKDIR /app

COPY package.json package-lock.json /app/
RUN npm install

COPY webpack.config.js /app/
COPY src/main/resources /app/src/main/resources
RUN npm run build


FROM composer AS composer

WORKDIR /app

COPY composer.* /app/
RUN composer install --no-dev --ignore-platform-reqs


FROM ghcr.io/programie/php-docker

ENV WEB_ROOT=/app/httpdocs

RUN install-php 8.4 && \
    a2enmod rewrite && \
    mkdir -p /app/cache && \
    chown www-data: /app/cache

COPY --from=composer /app/vendor /app/vendor
COPY --from=webpack /app/httpdocs/assets /app/httpdocs/assets
COPY --from=webpack /app/webpack.assets.json /app/webpack.assets.json

COPY bootstrap.php /app/
COPY httpdocs /app/httpdocs
COPY bin /app/bin
COPY src /app/src

RUN /app/bin/fetch-github-data.php
