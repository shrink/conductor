FROM php:7.4.10-cli-alpine3.12

WORKDIR /srv

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer global require hirak/prestissimo --dev --prefer-dist && \
    composer install --no-ansi --no-autoloader --no-interaction

COPY . ./
RUN composer dump-autoload

RUN composer validate-code || echo "error" > error.exit

RUN mv junit.xml artifacts/psalm.xml
RUN [[ ! -f error.exit ]] && exit 0
