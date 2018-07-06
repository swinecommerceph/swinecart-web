<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Check if User has the provided role then
     * proceed to the request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if(!app('Illuminate\Contracts\Auth\Guard')->guest()){
            if($request->user()->hasRole($role)){
                return $next($request);
            }
        }

        return ($request->ajax() || $request->wantsJson()) ? response('Unauthorized.', 401) : redirect('/home');
    }
}
