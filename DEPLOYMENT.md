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

The script stops on the first failed command and reports its line. It puts the site in maintenance mode, fast-forwards Git, installs the exact Composer and pnpm lockfiles, builds fingerprinted Vite assets, runs migrations, links public storage when the link is missing, rebuilds Laravel's production caches, restarts queue workers and brings the site back up. If a step fails the site stays in maintenance mode so visitors never see a half-deployed state; fix the error and run the script again, or run `php artisan up`.

Blog images from the old file manager are no longer imported on every deploy. Run `php artisan blog:import-media` once if a server still has posts that use them.

## One-time server setup

- `php artisan curator:token` — generates `CURATOR_GLIDE_TOKEN` in `.env` if it is missing.
- Social sign-in: set `GOOGLE_CLIENT_ID`/`GOOGLE_CLIENT_SECRET`, `FACEBOOK_CLIENT_ID`/`FACEBOOK_CLIENT_SECRET` and `ZALO_CLIENT_ID`/`ZALO_CLIENT_SECRET`. A provider's button stays hidden until both of its keys are set. The callback URLs are `https://<domain>/auth/{google|facebook|zalo}/callback`.

After deployment, verify the homepage, `/admin/settings`, a public form submission and the generated favicon/manifest URLs over HTTPS.
