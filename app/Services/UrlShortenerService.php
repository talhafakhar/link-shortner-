<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Support\Str;

class UrlShortenerService
{
    public function createShortUrl(string $originalUrl, ?string $customSlug = null, ?string $expiresAt = null): ShortUrl
    {
        $slug = $customSlug ?: $this->generateUniqueSlug();
        
        return ShortUrl::create(
            [
            'original_url' => $originalUrl,
            'slug' => $slug,
            'expires_at' => $expiresAt,
            ]
        );
    }

    public function findBySlug(string $slug): ?ShortUrl
    {
        return ShortUrl::where('slug', $slug)->first();
    }

    private function generateUniqueSlug(int $length = 6): string
    {
        do {
            $slug = Str::random($length);
        } while (ShortUrl::where('slug', $slug)->exists());

        return $slug;
    }
}
