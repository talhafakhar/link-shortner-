<?php

namespace Tests\Unit;

use App\Models\ShortUrl;
use App\Services\UrlShortenerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class UrlShortenerServiceTest extends TestCase
{
    use RefreshDatabase;

    private UrlShortenerService $urlShortenerService;

    protected function setUp(): void
    {
        parent::setUp();
        // $this->urlShortenerService = new UrlShortenerService();
        $this->urlShortenerService = app(UrlShortenerService::class);
    }

    public function test_it_creates_short_url_with_custom_slug(): void
    {
        $shortUrl = $this->urlShortenerService->createShortUrl(
            'https://example.com',
            'custom-slug'
        );

        $this->assertEquals('https://example.com', $shortUrl->original_url);
        $this->assertEquals('custom-slug', $shortUrl->slug);
        $this->assertNull($shortUrl->expires_at);
    }

    public function test_it_creates_short_url_with_auto_generated_slug(): void
    {
        $shortUrl = $this->urlShortenerService->createShortUrl('https://example.com');

        $this->assertEquals('https://example.com', $shortUrl->original_url);
        $this->assertNotNull($shortUrl->slug);
        $this->assertNull($shortUrl->expires_at);
    }

    public function test_it_creates_short_url_with_expiration(): void
    {
        $expiresAt = now()->addDay();
        $shortUrl = $this->urlShortenerService->createShortUrl(
            'https://example.com',
            null,
            $expiresAt
        );

        $this->assertEquals('https://example.com', $shortUrl->original_url);
        $this->assertEquals($expiresAt->toDateTimeString(), $shortUrl->expires_at->toDateTimeString());
    }

    public function test_it_finds_url_by_slug(): void
    {
        $shortUrl = ShortUrl::factory()->create(
            [
            'original_url' => 'https://example.com',
            'slug' => 'test-slug',
            ]
        );

        $foundUrl = $this->urlShortenerService->findBySlug('test-slug');

        $this->assertNotNull($foundUrl);
        $this->assertEquals($shortUrl->id, $foundUrl->id);
    }

    public function test_it_returns_null_for_nonexistent_slug(): void
    {
        $foundUrl = $this->urlShortenerService->findBySlug('nonexistent-slug');

        $this->assertNull($foundUrl);
    }
}