<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\BreederProfileRequest;
use App\Http\Requests\BreederPersonalProfileRequest;
use App\Http\Requests\BreederFarmProfileRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Product;
use App\Models\Image;
use App\Models\Video;
use App\Models\Breed;
use App\Models\Admin;
use App\Models\User;

use DB;
use Auth;
use Input;


class SpectatorController extends Controller
{
    protected $user;

    public function __construct()
    {
      $this->middleware('role:spectator');
      $this->middleware(function($request, $next){
          $this->user = Auth::user();

          return $next($request);
      });
    }

    public function index(Request $request)
    {
        return view('user.spectator.home');
    }

    public function viewUsers()
    {
        $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->paginate(10);
        return view(('user.spectator.users'), compact('users'));
    }

    public function viewProducts()
    {
        return view('user.spectator.products');
    }

    public function viewLogs()
    {
        return view('user.spectator.logs');
    }

    public function viewStatistics()
    {
        // $votes  = Lava::DataTable();
        //
        // $votes->addStringColumn('Food Poll')
        //       ->addNumberColumn('Votes')
        //       ->addRow(['Tacos',  rand(1000,5000)])
        //       ->addRow(['Salad',  rand(1000,5000)])
        //       ->addRow(['Pizza',  rand(1000,5000)])
        //       ->addRow(['Apples', rand(1000,5000)])
        //       ->addRow(['Fish',   rand(1000,5000)]);
        //
        // $lava->BarChart('Votes', $votes);

        return view('user.spectator.statistics');
    }

}
