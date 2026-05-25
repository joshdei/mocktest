<?php

namespace App\Http\Middleware;

use App\Services\StreakService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserLogin
{
    public function __construct(private StreakService $streakService) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $this->streakService->recordLogin(auth()->user());
        }

        return $next($request);
    }
}
