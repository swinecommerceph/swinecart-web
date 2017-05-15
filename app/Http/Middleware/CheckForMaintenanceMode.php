<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Route;

class CheckForMaintenanceMode
{


    protected $request;
    protected $app;


    protected function shouldPassThrough($request)
    {
        if(preg_match('/\b^admin\/*|^login|^\/|^logout|^home|^customer\/home|^breeder\/home|^spectator\/home\b\m+/', $request->path()) == 0){
            return true;
        }else{
            return false;
        }

    }

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance() &&
            $this->shouldPassThrough($request))
        {
            throw new HttpException(503);
        }

        return $next($request);
    }

}
