<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Cache\RateLimiter;
// use Illuminate\Http\Exceptions\ThrottleRequestsException;

class RateLimitShortUrlCreation
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $key = 'url-shortener:' . $request->ip();
        
        if ($this->limiter->tooManyAttempts($key, 2)) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Too many attempts. Please try again later.'
                ], 429
            );
        }
        
        $this->limiter->hit($key, 60);
        
        return $next($request);
    }
}
