<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class JWTRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {   
        $user = JWTAuth::parseToken()->toUser();

        if($user) {
            if($user->hasRole($role)) {
                return $next($request);
            }
            else {
                return response()->json(['error' => 'Unauthorized!'], 401); 
            }
        }
        else return response()->json(['error' => 'Unauthenticated!'], 401);
    }
}
