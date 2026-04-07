<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        $token = str_replace('Bearer ', '', $token);

        if ($token !== config('services.token.key')) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
