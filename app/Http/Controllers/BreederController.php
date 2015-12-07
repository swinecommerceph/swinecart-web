<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BreederController extends Controller
{

	/**
     * Create new UserController instance
     */
    public function __construct()
    {
        $this->middleware('role:breeder');
    }

	/**
	 * Show Home Page of breeder
	 * @return View
	 */
    public function index()
    {
    	return view('user.breeder.home');
    }
}
