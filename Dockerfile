FROM php:7.4-cli-alpine

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

WORKDIR /app

RUN adduser --disabled-password app \
  && chown app:app -R /app

USER app

COPY --chown=app:app bin bin
COPY --chown=app:app config config
COPY --chown=app:app public public
COPY --chown=app:app src src
COPY --chown=app:app .env composer.* ./

RUN composer install

COPY --chown=app:app . .

ENTRYPOINT ["sh"]
