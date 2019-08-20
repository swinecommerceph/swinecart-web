<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class JWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $access)
    {
        if($access == 'auth') {
            if(!JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Unauthenticated!'], 401);
            }
        }
        return $next($request);
    }
}
