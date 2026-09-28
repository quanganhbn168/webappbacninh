# Production deployment

The web server document root must point to the repository's `public` directory.

## Required environment values

```dotenv
APP_ENV=production
APP_DEBUG=false
CACHE_PREFIX=webappbacninh_cache_
SETTINGS_CACHE_ENABLED=true
SETTINGS_CACHE_MEMO=true
```

Keep the remaining database, mail, queue and application secrets in the server `.env`; never commit that file.

## Deploy

Run from the repository root:

```bash
bash deploy.sh
```

The script stops on the first failed command and reports its line. It performs a fast-forward Git pull, installs the exact pnpm lockfile, builds fingerprinted Vite assets, runs migrations, clears stale application/compiled caches and rebuilds Laravel's production config, event, route and view caches.

The script also runs `composer install --no-dev --optimize-autoloader`, so new PHP packages (for example `socialiteproviders/zalo`) are installed on deploy.

## One-time server setup

- `php artisan storage:link` — Curator serves uploaded and imported images from `/storage`.
- `php artisan curator:token` — generates `CURATOR_GLIDE_TOKEN` in `.env` if it is missing.
- Social sign-in: set `GOOGLE_CLIENT_ID`/`GOOGLE_CLIENT_SECRET`, `FACEBOOK_CLIENT_ID`/`FACEBOOK_CLIENT_SECRET` and `ZALO_CLIENT_ID`/`ZALO_CLIENT_SECRET`. A provider's button stays hidden until both of its keys are set. The callback URLs are `https://<domain>/auth/{google|facebook|zalo}/callback`.

After deployment, verify the homepage, `/admin/settings`, a public form submission and the generated favicon/manifest URLs over HTTPS.
