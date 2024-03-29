FROM ghcr.io/oliverdaviesltd/php:7.4-cli

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

ENV PATH="${PATH}:/app/vendor/bin"

WORKDIR /app

RUN apt-get update -yqq \
   && apt-get install -yqq \
    git \
    unzip \
   && adduser --disabled-password app \
   && chown app:app -R /app

USER app

COPY --chown=app:app bin bin
COPY --chown=app:app config config
COPY --chown=app:app public public
COPY --chown=app:app src src
COPY --chown=app:app .env composer.* ./

RUN composer install

COPY --chown=app:app . .

ENTRYPOINT ["bash"]
