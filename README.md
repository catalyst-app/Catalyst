# Catalyst - Facilitating Commissions

Catalyst served to facilitate the process of commissioning through a simple, unified, and mobile-friendly way for artists to easily list their prices, receive and track commissions, and much more.

Unfortunately, we have decided to shut down the project.  Hopefully someone else will revolutionize this space in the future!

Thanks to everyone who helped; we had a good run.

## Running Locally

Since this project is open-source, you are welcome to fork this project and run it locally for yourself.  However, please be aware that the ecosystem is quite complex and we provide no warranty nor maintenance.

We provide a Docker image for running the project locally.  To build it, use the following:

```sh
docker build -t catalyst .
```

This container exposes port `8080` and requires a few volumes:

- `/var/www/catalyst/keys`
  - You must generate a RSA private and public keypair using the following:
        ```sh
        openssl genrsa -des3 -out key.pem 4096 # any password will do
        openssl rsa -in key.pem -out private.pem -outform PEM
        openssl rsa -in private.pem -outform PEM -pubout -out public.pem
        ```

  - Then, place the private key, including the `------BEGIN...` and `END`, in a file `key.pem`;
  - Finally, place the contents of public.pem, **without** the `------BEGIN...` and `END`, into `key.pub`

Finally, there are **many** environment variables:

- Database configuration:
  - `DB_HOST` default `mariadb`
  - `DB_PORT` default `3306`
  - `DB_USER` default `mariadb`
  - `DB_PASS` default `mariadb`
  - `DB_NAME` default `catalyst`
- Communication:
  - `NO_REPLY_PASSWORD` is the password for email `no-reply@catalystapp.co` (hardcoded in `Email` class)
  - `SMTP_SERVER` default localhost
  - `SMTP_PORT` default 1234
- CAPTCHAs
  - All of these will not have any effect unless the site is moved out of development mode, which is currently not supported.
  - These are only kept for legacy reasons. All CAPTCHAs shown will say "for development only" and not provide any protection.
  - `EMAIL_LIST_CAPTCHA_SITE`, `EMAIL_LIST_CAPTCHA_SECRET`
  - `EMAIL_VERIFICATION_CAPTCHA_SITE`, `EMAIL_VERIFICATION_CAPTCHA_SECRET`
  - `LOGIN_CAPTCHA_SITE`, `LOGIN_CAPTCHA_SECRET`
  - `REGISTER_CAPTCHA_SITE`, `REGISTER_CAPTCHA_SECRET`
- Error logging
  - `ERROR_LOG_PASSWORD` is the password for email `error_logs@catalystapp.co` (hardcoded in `Email` class)
  - `DISCORD_BUG_WEBHOOK_TOKEN` allows sending error messages to Discord, should be format `channel-id/token`
  - `TELEGRAM_CHAT` and `TELEGRAM_TOKEN` allows sending error messages to Telegram; should be a chat ID and a bot token respectively

For simplicity, we provide a starter `docker-compose.yaml` which will startup a database.  For this, you should only need to set environment variables `MARIADB_ROOT_PASSWORD` and `DB_PASSWORD`, both of which can be random values.  You will also need to provide `./catalyst-keys` (as described above) and `./catalyst-data` (mirroring `external_assets` in the repository).

Please note that no SSL or security is provided in this docker compose configuration; **you must use a reverse proxy** like Traefik to provide SSL and other important features.  Additionally, this configuration exposes port 8081 as a database management interface; **you must not expose this port to the internet**.

If you want to use the Docker compose file with local development, set the image name to be the same as the one you built with `docker build`.

We provide two prebuilt packages:
- `ghcr.io/catalyst-app/catalyst:master`, everything in the project, as abandoned

Also, the following are not supported (although this is an incomplete list):
- thumbnail generation for uploaded images
- patreon integration for the about page
- external resource auto-updating
- production bundling/minification
- catl.st image shortener
