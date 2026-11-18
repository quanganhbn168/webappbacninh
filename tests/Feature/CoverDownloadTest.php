<?php

namespace Tests\Feature;

use App\Domain\Tools\Actions\FetchRemoteMedia;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use ZipArchive;

class CoverDownloadTest extends TestCase
{
    public function test_only_https_youtube_and_tiktok_media_hosts_are_allowed(): void
    {
        $fetch = new FetchRemoteMedia;

        foreach ([
            'https://img.youtube.com/vi/abcdefghijk/maxresdefault.jpg',
            'https://i.ytimg.com/vi/abcdefghijk/hqdefault.jpg',
            'https://p16-sign-va.tiktokcdn.com/obj/image.jpeg',
            'https://p77-sign.tiktokcdn-us.com/x.jpeg',
            'https://www.tikwm.com/video/media/hdplay/1.mp4',
        ] as $url) {
            $this->assertTrue($fetch->isAllowed($url), $url);
        }

        foreach ([
            'http://img.youtube.com/vi/x/default.jpg',
            'https://127.0.0.1/',
            'http://169.254.169.254/latest/meta-data/',
            'ftp://img.youtube.com/x',
            'file:///etc/passwd',
            'https://img.youtube.com.evil.example/x.jpg',
            'https://eviltiktokcdn.com/x.jpg',
            'https://user@img.youtube.com/x.jpg',
            'https://img.youtube.com:8080/x.jpg',
            'https://localhost/x.jpg',
        ] as $url) {
            $this->assertFalse($fetch->isAllowed($url), $url);
        }
    }

    public function test_internal_addresses_are_refused_without_any_request(): void
    {
        Http::fake();

        foreach (['http://127.0.0.1/admin', 'http://169.254.169.254/latest/meta-data/', 'https://webappbacninh.test/.env'] as $url) {
            $this->from('/anh-cover')->post(route('cover.download'), ['image_url' => $url, 'provider' => 'youtube'])
                ->assertRedirect('/anh-cover')->assertSessionHas('error');
            $this->from('/anh-cover')->post(route('cover.download.video'), ['video_url' => $url])
                ->assertRedirect('/anh-cover')->assertSessionHas('error');
        }

        Http::assertNothingSent();
    }

    public function test_thumbnail_downloads_from_an_allowed_host(): void
    {
        Http::fake(['img.youtube.com/*' => Http::response('JPEGDATA', 200, ['Content-Type' => 'image/jpeg'])]);

        $this->post(route('cover.download'), [
            'image_url' => 'https://img.youtube.com/vi/abcdefghijk/maxresdefault.jpg',
            'provider' => 'youtube',
            'filename' => 'Video hay quá!',
        ])->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="video-hay-qua.jpg"')
            ->assertContent('JPEGDATA');
    }

    public function test_oversized_files_are_refused(): void
    {
        Http::fake(['img.youtube.com/*' => Http::response('x', 200, ['Content-Length' => (string) (FetchRemoteMedia::IMAGE_MAX_BYTES + 1)])]);

        $this->from('/anh-cover')->post(route('cover.download'), [
            'image_url' => 'https://img.youtube.com/vi/abcdefghijk/maxresdefault.jpg',
            'provider' => 'youtube',
        ])->assertRedirect('/anh-cover')->assertSessionHas('error', 'File quá lớn.');
    }

    public function test_bulk_zip_skips_refused_links_and_caps_the_list(): void
    {
        Http::fake(['img.youtube.com/*' => Http::response('IMG', 200)]);

        $response = $this->post(route('cover.download.bulk'), ['items' => [
            ['image_url' => 'https://img.youtube.com/vi/aaaaaaaaaaa/default.jpg', 'provider' => 'youtube', 'filename' => 'Một'],
            ['image_url' => 'http://127.0.0.1/secret', 'provider' => 'youtube', 'filename' => 'Hai'],
            ['image_url' => 'https://img.youtube.com/vi/bbbbbbbbbbb/default.jpg', 'provider' => 'youtube', 'filename' => 'Một'],
        ]])->assertOk();

        $zip = new ZipArchive;
        $zip->open($response->baseResponse->getFile()->getPathname());
        $names = array_map(fn (int $i): string => $zip->getNameIndex($i), range(0, $zip->numFiles - 1));
        $zip->close();
        $this->assertSame(['mot.jpg', 'mot-2.jpg'], $names);
        Http::assertSentCount(2);

        $tooMany = array_fill(0, 51, ['image_url' => 'https://img.youtube.com/vi/aaaaaaaaaaa/default.jpg', 'provider' => 'youtube']);
        $this->from('/bulk-anh-cover')->post(route('cover.download.bulk'), ['items' => $tooMany])->assertSessionHasErrors('items');
    }

    public function test_bulk_with_no_usable_link_returns_an_error(): void
    {
        Http::fake();

        $this->from('/bulk-anh-cover')->post(route('cover.download.bulk'), ['items' => [
            ['image_url' => 'http://10.0.0.1/x', 'provider' => 'youtube'],
        ]])->assertRedirect('/bulk-anh-cover')->assertSessionHas('error');
        Http::assertNothingSent();
    }

    public function test_video_is_streamed_from_an_allowed_host(): void
    {
        Http::fake(['www.tikwm.com/*' => Http::response('MP4DATA', 200)]);

        $response = $this->post(route('cover.download.video'), [
            'video_url' => 'https://www.tikwm.com/video/media/hdplay/1.mp4',
            'filename' => 'clip',
        ])->assertOk()->assertDownload('clip.mp4');

        $this->assertSame('MP4DATA', $response->streamedContent());
    }
}
