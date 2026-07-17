<?php

declare(strict_types=1);

function base_path(string $path = ''): string
{
    $base = dirname(__DIR__, 2);
    return $path === '' ? $base : $base . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
}

function resource_path(string $path = ''): string
{
    return base_path('resources' . ($path !== '' ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : ''));
}

function public_path(string $path = ''): string
{
    return base_path('public' . ($path !== '' ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : ''));
}

function config(?string $key = null, mixed $default = null): mixed
{
    static $items;
    if ($items === null) {
        $items = require base_path('config/app.php');
    }
    if ($key === null) {
        return $items;
    }
    return $items[$key] ?? $default;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function local_base_url(): string
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
    $directory = str_replace('\\', '/', dirname($scriptName));
    $directory = trim($directory);

    if ($directory === '' || $directory === '.' || $directory === '/') {
        return '';
    }

    return '/' . trim($directory, '/');
}

function url(string $path = ''): string
{
    if (preg_match('~^(https?:)?//|^(mailto:|tel:|#)~', $path)) {
        return $path;
    }
    return local_base_url() . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('public/' . ltrim($path, '/'));
}

function absolute_url(string $path = ''): string
{
    if (preg_match('~^https?://~', $path)) {
        return $path;
    }
    return rtrim((string) config('site_url'), '/') . '/' . ltrim($path, '/');
}

function view(string $path, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require resource_path('views/' . str_replace('.', '/', $path) . '.php');
}

function themes(): array
{
    static $themes;
    if ($themes === null) {
        $themes = require base_path('app/Data/themes.php');
    }
    return $themes;
}

function theme_by_slug(string $slug): ?array
{
    foreach (themes() as $theme) {
        if ($theme['slug'] === $slug) {
            return $theme;
        }
    }
    return null;
}

function theme_url(array $theme): string
{
    return url('chi-tiet-giao-dien.php?slug=' . rawurlencode($theme['slug']));
}

function related_themes(array $current, int $limit = 3): array
{
    $items = array_values(array_filter(themes(), static function (array $theme) use ($current): bool {
        return $theme['slug'] !== $current['slug'];
    }));

    usort($items, static function (array $a, array $b) use ($current): int {
        $scoreA = ($a['industry'] === $current['industry'] ? 2 : 0) + ($a['type'] === $current['type'] ? 1 : 0);
        $scoreB = ($b['industry'] === $current['industry'] ? 2 : 0) + ($b['type'] === $current['type'] ? 1 : 0);
        return $scoreB <=> $scoreA ?: $b['featured'] <=> $a['featured'];
    });

    return array_slice($items, 0, $limit);
}

function money(int|float $value): string
{
    return number_format((float) $value, 0, ',', '.') . 'đ';
}

function projects(): array
{
    static $projects;
    if ($projects === null) {
        $projects = require base_path('app/Data/projects.php');
    }
    return $projects;
}

function project_by_slug(string $slug): ?array
{
    foreach (projects() as $project) {
        if (($project['slug'] ?? '') === $slug) {
            return $project;
        }
    }
    return null;
}

function project_url(array $project): string
{
    return url('chi-tiet-du-an.php?slug=' . rawurlencode((string) $project['slug']));
}

function related_projects(array $current, int $limit = 3): array
{
    $items = array_values(array_filter(projects(), static function (array $project) use ($current): bool {
        return ($project['slug'] ?? '') !== ($current['slug'] ?? '');
    }));

    usort($items, static function (array $a, array $b) use ($current): int {
        $scoreA = (($a['industry'] ?? '') === ($current['industry'] ?? '') ? 2 : 0)
            + (($a['category'] ?? '') === ($current['category'] ?? '') ? 1 : 0);
        $scoreB = (($b['industry'] ?? '') === ($current['industry'] ?? '') ? 2 : 0)
            + (($b['category'] ?? '') === ($current['category'] ?? '') ? 1 : 0);

        return $scoreB <=> $scoreA ?: ($b['featured'] ?? 0) <=> ($a['featured'] ?? 0);
    });

    return array_slice($items, 0, $limit);
}


function articles(): array
{
    static $articles;
    if ($articles === null) {
        $articles = require base_path('app/Data/articles.php');
    }
    return $articles;
}

function article_by_slug(string $slug): ?array
{
    foreach (articles() as $article) {
        if (($article['slug'] ?? '') === $slug) {
            return $article;
        }
    }
    return null;
}

function article_url(array $article): string
{
    return url('chi-tiet-bai-viet.php?slug=' . rawurlencode((string) $article['slug']));
}

function related_articles(array $current, int $limit = 3): array
{
    $items = array_values(array_filter(articles(), static function (array $article) use ($current): bool {
        return ($article['slug'] ?? '') !== ($current['slug'] ?? '');
    }));

    usort($items, static function (array $a, array $b) use ($current): int {
        $scoreA = (($a['category'] ?? '') === ($current['category'] ?? '') ? 2 : 0);
        $scoreB = (($b['category'] ?? '') === ($current['category'] ?? '') ? 2 : 0);
        return $scoreB <=> $scoreA ?: ($b['featured'] ?? 0) <=> ($a['featured'] ?? 0);
    });

    return array_slice($items, 0, $limit);
}
