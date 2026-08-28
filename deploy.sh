#!/usr/bin/env bash

set -Eeuo pipefail

trap 'status=$?; printf "Deploy failed at line %s (exit %s)\n" "${BASH_LINENO[0]}" "$status" >&2; exit "$status"' ERR

run_step() {
    printf '\n==> %s\n' "$1"
    shift
    "$@"
}

run_step "Pull source" git pull --ff-only
run_step "Install frontend dependencies" pnpm install --frozen-lockfile
run_step "Build versioned frontend assets" pnpm run build
run_step "Run database migrations" php artisan migrate --force
run_step "Clear application cache" php artisan cache:clear
run_step "Clear compiled Laravel state" php artisan optimize:clear
run_step "Build production Laravel caches" php artisan optimize

printf '\nDeploy completed successfully.\n'
