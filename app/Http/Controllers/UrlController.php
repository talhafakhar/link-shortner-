<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShortUrlRequest;
use App\Services\AnalyticsService;
use App\Services\UrlShortenerService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class UrlController extends Controller
{
    private $urlShortenerService;
    private $analyticsService;

    public function __construct(UrlShortenerService $urlShortenerService, AnalyticsService $analyticsService)
    {
        $this->urlShortenerService = $urlShortenerService;
        $this->analyticsService = $analyticsService;
    }

    public function index(): View
    {
        return view('home');
    }

    public function isExpired()
    {
        return $this->expires_at && Carbon::parse($this->expires_at)->isPast();
    }
    public function create(CreateShortUrlRequest $request): JsonResponse
    {
        $shortUrl = $this->urlShortenerService->createShortUrl(
            $request->input('original_url'),
            $request->input('custom_slug'),
            $request->input('expires_at')
        );

        return response()->json(
            [
            'success' => true,
            'data' => [
                'original_url' => $shortUrl->original_url,
                'short_url' => route('redirect', $shortUrl->slug),
                'slug' => $shortUrl->slug,
                'expires_at' => $shortUrl->expires_at ?? null,
            ]
            ],
            200
        );
    }



    public function redirect(string $slug, Request $request): RedirectResponse
    {
        $shortUrl = Cache::remember(
            'short_url:' . $slug,
            3600,
            function () use ($slug) {
                return $this->urlShortenerService->findBySlug($slug);
            }
        );

        if (!$shortUrl) {
            // return redirect()->route('home')->with('error', 'URL not found');
            abort(404, 'URL not found');
        }
        if ($shortUrl->expires_at && now() >= ($shortUrl->expires_at)) {
            Cache::forget('short_url:' . $slug);
            return redirect()->route('home')->with('error', 'URL has expired');
        }
       
        $this->analyticsService->trackVisit($shortUrl, $request);

        return redirect($shortUrl->original_url);
    }

    public function analytics(string $slug): View|RedirectResponse
    {
        $shortUrl = $this->urlShortenerService->findBySlug($slug);

        if (!$shortUrl) {
            return redirect()->route('home')->with('error', 'URL not found');
        }

        $analytics = $this->analyticsService->getUrlAnalytics($shortUrl);

        return view(
            'analytics',
            [
            'shortUrl' => $shortUrl,
            'analytics' => $analytics,
            ]
        );
    }
}
