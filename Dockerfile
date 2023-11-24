FROM trafex/php-nginx:latest AS base

  WORKDIR /var/www/catalyst

  USER root

  RUN apk add --no-cache php82-pdo_mysql

# install dependencies
FROM composer AS composer

  WORKDIR /
  COPY src/php/composer.json .
  COPY src/php/composer.lock .

  RUN composer install --optimize-autoloader --no-interaction --no-progress

# prep
FROM base AS with-deps

  COPY src src
  COPY --from=composer /vendor src/php/vendor

  COPY api api
  COPY internal_assets internal_assets
  COPY www www

  COPY nginx.conf /etc/nginx/conf.d/default.conf

  USER nobody

  # db
  ENV DB_HOST=mariadb
  ENV DB_PORT=3306
  ENV DB_USER=mariadb
  ENV DB_PASS=mariadb
  ENV DB_NAME=catalyst

  # recaptcha; not used when DEVEL=false, so we provide stupid defaults
  ENV EMAIL_LIST_CAPTCHA_SITE=.
  ENV EMAIL_LIST_CAPTCHA_SECRET=.
  ENV EMAIL_VERIFICATION_CAPTCHA_SITE=.
  ENV EMAIL_VERIFICATION_CAPTCHA_SECRET=.
  ENV LOGIN_CAPTCHA_SITE=.
  ENV LOGIN_CAPTCHA_SECRET=.
  ENV REGISTER_CAPTCHA_SITE=.
  ENV REGISTER_CAPTCHA_SECRET=.

  # email
  ENV SMTP_SERVER=mailserver
  ENV SMTP_PORT=465
  ENV NO_REPLY_PASSWORD=

  # error reporting
  ENV ERROR_LOG_PASSWORD=
  ENV DISCORD_BUG_WEBHOOK_TOKEN=
  ENV TELEGRAM_TOKEN=
  ENV TELEGRAM_CHAT=

  EXPOSE 8080
