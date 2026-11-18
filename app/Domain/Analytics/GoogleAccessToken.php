<?php

namespace App\Domain\Analytics;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Cache;
use RuntimeException;

/**
 * Read-only OAuth token of the dashboard's service account, shared by the GA4 and
 * Search Console calls and cached until shortly before it expires.
 */
class GoogleAccessToken
{
    private const SCOPES = [
        'https://www.googleapis.com/auth/analytics.readonly',
        'https://www.googleapis.com/auth/webmasters.readonly',
    ];

    public function credentialsPath(): string
    {
        return (string) config('services.google_analytics.credentials');
    }

    public function hasCredentials(): bool
    {
        return is_file($this->credentialsPath());
    }

    public function get(): string
    {
        return Cache::remember('analytics.google-token', now()->addMinutes(50), function (): string {
            if (! $this->hasCredentials()) {
                throw new RuntimeException('Chưa có file khóa service account.');
            }

            $token = (new ServiceAccountCredentials(self::SCOPES, $this->credentialsPath()))->fetchAuthToken();

            return (string) ($token['access_token'] ?? throw new RuntimeException('Google không trả về access token.'));
        });
    }
}
