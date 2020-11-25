# This is my typical Dockerfile pattern - which basically is to use multistages
# https://docs.docker.com/develop/develop-images/multistage-build/
# and ARGs which allows me to hadnle images versioning (even dapp versioning)
# dynamically from a CI e.g.
# In this way I can easily extend from a base image adding only the specific
# requirements for new ones while keeping a solid base between all of them.
# This is important in case of sticking with immutability which I believe is
# ideal partner to go with Docker - read more about immutable infra and its
# benefits here https://www.digitalocean.com/community/tutorials/what-is-immutable-infrastructure

# By defining MAJOR.MINOR without PATCH we ensure to receive security and bug
# fixes updates.
ARG PHP_VERSION=7.4-fpm-buster
ARG COMPOSER_VERSION=latest

#
# base stage
#
FROM php:$PHP_VERSION AS base

ARG REDIS_VERSION=5.3.1

RUN apt-get update && apt-get install -y --no-install-recommends --no-install-suggests \
        libzip-dev \
        unzip \
        zip \
    && pecl install \
        redis-$REDIS_VERSION \
    && docker-php-ext-enable \
        redis \
    && docker-php-ext-install -j$(nproc) \
        zip \
    && rm -rf /tmp/* /var/lib/apt/lists/*

COPY ./docker/php/config/base.ini /usr/local/etc/php/conf.d/10-base.ini

WORKDIR /var/www

#
# dev stage
#
FROM composer:$COMPOSER_VERSION AS composer
FROM base AS dev

# In this case is not possible to get benefit from the MAJOR.MINOR strategy since
# PECL repo does not provide this feature.
ARG RUNKIT_VERSION=4.0.0a2
ARG XDEBUG_VERSION=2.9.8

RUN apt-get update && apt-get install -y --no-install-recommends --no-install-suggests \
        git \
        # Yeah! Vim fellow here :D
        vim \
    && pecl install \
        # For monkey patch.
        runkit7-$RUNKIT_VERSION \
        xdebug-$XDEBUG_VERSION \
    && docker-php-ext-enable \
        runkit7 \
        xdebug \
    # Install Symfony CLI.
    && curl https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
    && rm -rf /tmp/* /var/lib/apt/lists/*

# Extract Composer binary from its official image instead of building it.
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./docker/php/config/dev.ini /usr/local/etc/php/conf.d/50-dev.ini

# We could have here a production stage where basically we extend from our base
# image and create and optimize an image whith production code (artifact) to be
# deployed into our container orchestrator.
