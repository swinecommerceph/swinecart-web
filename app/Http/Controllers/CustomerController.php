<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Create new UserController instance
     */
    public function __construct()
    {
        $this->middleware('role:customer');
    }

    /**
     * Show Home Page of breeder
     * @return View
     */
    public function index()
    {
        return view('user.customer.home');
    }
}
