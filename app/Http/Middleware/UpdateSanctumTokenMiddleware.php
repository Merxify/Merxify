<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class UpdateSanctumTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()) {
            return $next($request);
        }

        $token = $request->user()->currentAccessToken();
        $token->update([
            'expires_at' => Carbon::now()->addMinutes(60),
        ]);

        return $next($request);
    }
}
