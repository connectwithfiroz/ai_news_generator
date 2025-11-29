<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\TokenService;

class CheckTokenBalance
{
    public function handle($request, Closure $next, $tokensRequired)
    {
        $tokenService = new TokenService();

        if (! $tokenService->checkTokens($tokensRequired)) {
            return response()->json([
                'error' => 'Not enough tokens'
            ], 402);
        }

        return $next($request);
    }
}

