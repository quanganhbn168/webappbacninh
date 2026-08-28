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

Composer is intentionally not run by this script. Run `composer install --no-dev --optimize-autoloader` separately only when `composer.lock` changes or the server dependencies have not been prepared.

After deployment, verify the homepage, `/admin/settings`, a public form submission and the generated favicon/manifest URLs over HTTPS.
