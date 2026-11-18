<?php

namespace App\Domain\Tools\Actions;

use App\Domain\Tools\Exceptions\RemoteMediaRefused;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Throwable;

/**
 * Downloads a thumbnail or video for the cover tools. The URL comes from the visitor,
 * so the server only fetches https URLs on the YouTube/TikTok media hosts (redirects
 * included) and stops at a size limit; anything else would let visitors make the
 * server read internal addresses (SSRF) or exhaust it with huge files.
 */
final class FetchRemoteMedia
{
    public const IMAGE_MAX_BYTES = 10 * 1024 * 1024;

    public const VIDEO_MAX_BYTES = 300 * 1024 * 1024;

    /** Hosts (and their subdomains) that YoutubeService / TiktokService hand out. */
    private const ALLOWED_HOSTS = [
        'img.youtube.com',
        'ytimg.com',
        'tiktokcdn.com',
        'tiktokcdn-us.com',
        'tiktokcdn-eu.com',
        'tiktokv.com',
        'tiktokv.us',
        'ibyteimg.com',
        'byteimg.com',
        'tikwm.com',
    ];

    public function isAllowed(string $url): bool
    {
        $parts = parse_url($url);
        $host = strtolower((string) ($parts['host'] ?? ''));

        if (($parts['scheme'] ?? null) !== 'https' || $host === '' || isset($parts['user']) || isset($parts['port'])) {
            return false;
        }

        foreach (self::ALLOWED_HOSTS as $allowed) {
            if ($host === $allowed || str_ends_with($host, '.'.$allowed)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The whole file as a string (images).
     *
     * @throws RemoteMediaRefused
     */
    public function read(string $url, int $maxBytes = self::IMAGE_MAX_BYTES): string
    {
        $stream = $this->open($url, $maxBytes);
        $body = '';

        while (! $stream->eof()) {
            $body .= $stream->read(64 * 1024);
            if (strlen($body) > $maxBytes) {
                $stream->close();
                throw new RemoteMediaRefused('File quá lớn.');
            }
        }

        return $body;
    }

    /**
     * An open stream (videos); the caller copies it with copyTo() so the limit holds.
     *
     * @throws RemoteMediaRefused
     */
    public function open(string $url, int $maxBytes): StreamInterface
    {
        if (! $this->isAllowed($url)) {
            throw new RemoteMediaRefused('Chỉ tải được ảnh/video từ YouTube hoặc TikTok.');
        }

        try {
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->withOptions([
                    'stream' => true,
                    'allow_redirects' => [
                        'max' => 5,
                        'protocols' => ['https'],
                        'on_redirect' => function (RequestInterface $request, ResponseInterface $response, UriInterface $uri): void {
                            if (! $this->isAllowed((string) $uri)) {
                                throw new RemoteMediaRefused('Link chuyển hướng tới nơi không được phép.');
                            }
                        },
                    ],
                ])
                ->get($url);
        } catch (RemoteMediaRefused $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            $previous = $exception->getPrevious();
            throw $previous instanceof RemoteMediaRefused
                ? $previous
                : new RemoteMediaRefused('Không tải được file.', previous: $exception);
        }

        if (! $response->successful()) {
            throw new RemoteMediaRefused('Không tải được file (mã '.$response->status().').');
        }

        if ((int) $response->header('Content-Length') > $maxBytes) {
            throw new RemoteMediaRefused('File quá lớn.');
        }

        return $response->toPsrResponse()->getBody();
    }

    /**
     * Copies the stream to an output callback, stopping at $maxBytes.
     */
    public static function copyTo(StreamInterface $stream, int $maxBytes, callable $write): void
    {
        $sent = 0;
        while (! $stream->eof() && $sent < $maxBytes) {
            $chunk = $stream->read(min(64 * 1024, $maxBytes - $sent));
            $sent += strlen($chunk);
            $write($chunk);
        }
        $stream->close();
    }
}
