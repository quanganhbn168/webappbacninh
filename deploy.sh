#!/usr/bin/env bash

set -Eeuo pipefail

cd "$(dirname "$0")"

MAINTENANCE=0

trap 'status=$?; printf "\nDeploy failed at line %s (exit %s).\n" "${BASH_LINENO[0]}" "$status" >&2; if [ "$MAINTENANCE" -eq 1 ]; then printf "The site is still in maintenance mode. Fix the error and run deploy.sh again, or run: php artisan up\n" >&2; fi; exit "$status"' ERR

run_step() {
    printf '\n==> %s\n' "$1"
    shift
    "$@"
}

run_step "Pull source" git pull --ff-only

# Visitors get a 503 page instead of errors while vendor/ and the database change.
run_step "Enable maintenance mode" php artisan down --retry=15
MAINTENANCE=1

run_step "Install PHP dependencies" composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
run_step "Install frontend dependencies" pnpm install --frozen-lockfile
run_step "Build versioned frontend assets" pnpm run build
run_step "Run database migrations" php artisan migrate --force

if [ ! -L public/storage ]; then
    run_step "Link public storage for uploaded images" php artisan storage:link
fi

run_step "Clear compiled Laravel state and cache" php artisan optimize:clear
run_step "Build production Laravel caches" php artisan optimize
run_step "Restart queue workers" php artisan queue:restart

run_step "Disable maintenance mode" php artisan up
MAINTENANCE=0

printf '\nDeploy completed successfully.\n'
