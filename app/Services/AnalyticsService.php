<?php

namespace App\Services;

use App\Models\ShortUrl;
use App\Models\UrlVisit;
use Illuminate\Http\Request;

class AnalyticsService
{
    public function trackVisit(ShortUrl $shortUrl, Request $request): void
    {
        $shortUrl->incrementVisits();
        
        UrlVisit::create([
            'short_url_id' => $shortUrl->id,
            'ip_address' => $request->ip(),
        ]);
    }

    public function getUrlAnalytics(ShortUrl $shortUrl): array
    {
        return [
            'total_visits' => $shortUrl->visits,
            'created_at' => $shortUrl->created_at,
            'expires_at' => $shortUrl->expires_at,
            'recent_visits' => $shortUrl->visits()
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($visit) {
                    return [
                        'ip_address' => $visit->ip_address,
                        'visited_at' => $visit->created_at,
                    ];
                }),
        ];
    }
}