<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ShortUrl;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->withoutMiddleware(
            [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            'limit',
            ]
        );    
    }
    public function test_home_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_it_creates_short_url(): void
    {
        $response = $this->postJson(
            'api/shorten', [
            'original_url' => 'https://example.com',
            ]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
            'success',
            'data' => [
                'original_url',
                'short_url',
                'slug',
                'expires_at',
            ]
            ]
        );

        $this->assertDatabaseHas(
            'short_urls', [
            'original_url' => 'https://example.com',
            ]
        );
    }

    public function test_it_creates_short_url_with_custom_slug(): void
    {
        $response = $this->postJson(
            '/api/shorten', [
            'original_url' => 'https://example.com',
            'custom_slug' => 'test-slug',
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(
            [
            'success' => true,
            'data' => [
                'original_url' => 'https://example.com',
                'slug' => 'test-slug',
            ]
            ]
        );

        $this->assertDatabaseHas(
            'short_urls', [
            'original_url' => 'https://example.com',
            'slug' => 'test-slug',
            ]
        );
    }

    public function test_it_rejects_duplicate_slug(): void
    {
        ShortUrl::factory()->create(
            [
            'slug' => 'existing-slug',
            ]
        );

        $response = $this->postJson(
            'api/shorten', [
            'original_url' => 'https://example.com',
            'custom_slug' => 'existing-slug',
            ]
        );

        $response->assertStatus(422);
    }

    public function test_it_redirects_to_original_url(): void
    {
        $shortUrl = ShortUrl::factory()->create(
            [
            'original_url' => 'https://example.com',
            'slug' => 'test-slug',
            ]
        );

        $response = $this->get('/'.$shortUrl->slug);

        $response->assertRedirect($shortUrl->original_url);
    }

    public function test_it_returns_error_for_expired_url(): void
    {
        $shortUrl = ShortUrl::factory()->create(
            [
            'original_url' => 'https://example.com',
            'slug' => 'expired-slug',
            'expires_at' => now()->subDay(),
            ]
        );

        $response = $this->get('/'.$shortUrl->slug);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'URL has expired');
    }

    public function test_it_shows_analytics_page(): void
    {
        $shortUrl = ShortUrl::factory()->create(
            [
            'original_url' => 'https://example.com',
            'slug' => 'analytics-slug',
            ]
        );

        $response = $this->get('/analytics/'.$shortUrl->slug);

        $response->assertStatus(200);
        $response->assertViewIs('analytics');
        $response->assertViewHas('shortUrl', $shortUrl);
    }
}
