<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\BreederProfileRequest;
use App\Http\Requests\BreederPersonalProfileRequest;
use App\Http\Requests\BreederFarmProfileRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Product;
use App\Models\Image;
use App\Models\Video;
use App\Models\Breed;
use App\Models\User;

use DB;
use Auth;
class AdminController extends Controller
{
  protected $user;

  /**
   * Create new BreederController instance
   */
  public function __construct()
  {
      $this->middleware('role:admin');
      $this->user = Auth::user();
  }

  /**
   * Show Home Page of breeder
   *
   * @param  Request $request
   * @return View
   */
    public function index(Request $request)
    {
        //$users = User::all();
        //$users = DB::table('users')->join('roles', 'users.userable_id', '=' , 'roles.id')->where('users.email_verified','=', 1, 'and', 'roles.id','!=', 1)->get();
        $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->where('role_user.role_id','!=',1)->where('users.email_verified','=', 1)->get();
        //dd($users);
        return view('user.admin.home', compact('users'));
    }

    public function displayAllUsers(){
      $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->where('role_user.role_id','!=',1)->where('users.email_verified','=', 1)->get();
      return view('user.admin.home', compact('users'));
    }

    public function displayApprovedBreeders(){
      $users =DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->where('roles.id','=', 2)->where('users.email_verified','=', 1)->get();
      return view('user.admin.home', compact('users'));
      //dd($users);
      //@TODO return to view

    }

    public function displayApprovedCustomer(){
      $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->where('roles.id','=', 3)->where('users.email_verified','=', 1)->get();
      return view('user.admin.home', compact('users'));
      //dd($users);
      //@TODO return to view
    }

    public function displayPendingCustomers(){
      $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->where('users.email_verified', '=', 0)->where('roles.id', '=', 3)->get();
      return view('user.admin.home', compact('users'));
      //dd($users);
      //@TODO return to view
    }
}
