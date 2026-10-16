<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
   public function handle(Request $request, Closure $next): Response
{
    $token = $request->bearerToken();

    // Uncomment only for debugging
    // logger()->info([
    //     'request_token' => $token,
    //     'config_token' => config('services.token.key'),
    // ]);

    if (empty($token)) {
        return response()->json([
            'message' => 'Authorization token missing'
        ], 401);
    }

    if ($token !== config('services.token.key')) {
        return response()->json([
            'message' => 'Invalid token'
        ], 401);
    }

    return $next($request);
}
}
