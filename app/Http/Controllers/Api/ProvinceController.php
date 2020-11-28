<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use JWTAuth;

class ProvinceController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt:auth');
        $this->middleware(function($request, $next) {
            $this->user = JWTAuth::user();
            return $next($request);
        });
    }

    public function getProvinces(Request $request)
    {
        return response()->json([
            'data' => [
                'provinces' => 'hello',
            ]
        ], 200);
    }
}
