# Production deployment

The web server document root must point to the repository's `public` directory. The server needs Node 20 or newer and pnpm (not npm).

## Required environment values

```dotenv
APP_ENV=production
APP_DEBUG=false
CACHE_PREFIX=webappbacninh_cache_
SETTINGS_CACHE_ENABLED=true
SETTINGS_CACHE_MEMO=true
SEPAY_WEBHOOK_TOKEN=   # API key from my.sepay.vn; the SePay webhook answers 503 until it is set
```

Keep the remaining database, mail, queue and application secrets in the server `.env`; never commit that file.

## Deploy

Run from the repository root:

```bash
bash deploy.sh
```

The script stops on the first failed command and reports its line. It puts the site in maintenance mode, fast-forwards Git, installs the exact Composer and pnpm lockfiles, builds fingerprinted Vite assets, runs migrations, links public storage when the link is missing, rebuilds Laravel's production caches, restarts queue workers and brings the site back up. If a step fails the site stays in maintenance mode so visitors never see a half-deployed state; fix the error and run the script again, or run `php artisan up`.

PHP must be able to write to `storage` and `bootstrap/cache`. When the script runs as root it hands both to the web user, `www` by default (override with `WEB_USER=www-data bash deploy.sh`). After running any other `artisan` command as root, restore ownership yourself: `chown -R www: storage bootstrap/cache`.

## Rebuild the database from scratch

Only when the database may be thrown away: **everything in it is deleted** (contact requests, orders, accounts and anything typed in the admin) and rebuilt from the seeders.

```bash
git pull --ff-only
FRESH=1 bash deploy.sh
php artisan app:setup-admin --email=<admin email>
```

`FRESH=1` runs `migrate:fresh --seed` instead of `migrate` and removes the old `storage/app/public/blog` upload folder. Pull first, so the script that runs is already the new one. `app:setup-admin` asks for the password (leave it empty to have one generated and shown once). With `APP_ENV=production` the seeders also add the Google Analytics tag.

## One-time server setup

- `php artisan curator:token` — generates `CURATOR_GLIDE_TOKEN` in `.env` if it is missing.
- Social sign-in: set `GOOGLE_CLIENT_ID`/`GOOGLE_CLIENT_SECRET`, `FACEBOOK_CLIENT_ID`/`FACEBOOK_CLIENT_SECRET` and `ZALO_CLIENT_ID`/`ZALO_CLIENT_SECRET`. A provider's button stays hidden until both of its keys are set. The callback URLs are `https://<domain>/auth/{google|facebook|zalo}/callback`.

## Web server

Image uploads go up to 30 MB. Set PHP `upload_max_filesize = 32M`, `post_max_size = 40M` and `memory_limit = 512M` (resizing a very tall screenshot needs it), and in Nginx `client_max_body_size 40m;`.

Enable gzip or Brotli for `text/css`, `application/javascript`, `image/svg+xml` and `text/html`, and long-lived caching for `/build/` (file names are fingerprinted by Vite), for example in Nginx:

```nginx
gzip on;
gzip_types text/css application/javascript image/svg+xml application/json;
# ^~ so a panel's own "location ~ .*\.(js|css)$" rule (aaPanel: 12 hours) does not win.
location ^~ /build/ { expires 1y; add_header Cache-Control "public, immutable"; }
```

During maintenance mode the branded page `resources/views/errors/503.blade.php` is pre-rendered by `php artisan down --render`.

After deployment, verify the homepage, `/admin/settings`, a public form submission and the generated favicon/manifest URLs over HTTPS.
