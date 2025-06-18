<?php

namespace Webkul\PhoneAuth\Http\Middleware;

use Closure;

class PhoneVerified
{
    public function handle($request, Closure $next)
    {
        if (!$request->user() || !$request->user()->phone_verified) {
            return response()->json(['message' => 'Phone number not verified.'], 403);
        }
        return $next($request);
    }
}
