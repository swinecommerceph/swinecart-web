<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\BreederProfileRequest;
use App\Http\Requests\BreederPersonalProfileRequest;
use App\Http\Requests\BreederFarmProfileRequest;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Breeder;
use App\Models\FarmAddress;
use App\Models\Product;
use App\Models\Image;
use App\Models\Video;
use App\Models\Breed;
use App\Models\User;
use Mail;

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
   * Helper functions for retrieving all user data
   *
   * @param  none
   * @return users
   */
  public function retrieveAllUsers(){
    $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->get();
    return $users;
  }

  /**
   * Helper functions for retrieving all user data
   *
   * @param  none
   * @return count of all approved user
   */
  public function userCount(){
    $count = DB::table('users')
                    ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                    ->join('roles', 'role_user.role_id','=','roles.id')
                    ->where('role_user.role_id','!=',1)
                    ->where('users.email_verified','=', 1)
                    ->where('users.deleted_at','=', NULL)
                    //->where('users.is_blocked','=', 0)
                    ->count();
    return $count;
  }
  /**
   * Helper functions for retrieving all user data
   *
   * @param  none
   * @return count of all approved breeders
   */
  public function breederCount(){
    $count = DB::table('users')
                    ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                    ->join('roles', 'role_user.role_id','=','roles.id')
                    ->where('role_user.role_id','=',2)
                    ->where('users.email_verified','=', 1)
                    ->where('users.is_blocked','=', 0)
                    ->where('users.deleted_at','=', NULL)
                    ->count();
    return $count;
  }

  /**
   * Helper functions for retrieving all user data
   *
   * @param  none
   * @return count of all customers
   */
  public function customerCount(){
    $count = DB::table('users')
                    ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                    ->join('roles', 'role_user.role_id','=','roles.id')
                    ->where('role_user.role_id','=',3)
                    ->where('users.email_verified','=', 1)
                    ->where('users.is_blocked','=', 0)
                    ->where('users.deleted_at','=', NULL)
                    ->count();
    return $count;
  }

  public function pendingUserCount(){
    $count = DB::table('users')
                    ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                    ->join('roles', 'role_user.role_id','=','roles.id')
                    ->where('role_user.role_id','=',2)
                    ->where('users.approved','=', 0)
                    ->where('users.deleted_at','=', NULL)
                    ->count();
    return $count;
  }

  public function blockedUserCount(){
    $count = DB::table('users')
                    ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                    ->join('roles', 'role_user.role_id','=','roles.id')
                    ->where('role_user.role_id','!=',1)
                    ->where('users.email_verified','=', 1)
                    ->where('users.is_blocked','=', 1)
                    ->where('users.deleted_at','=', NULL)
                    ->count();
    return $count;
  }



  /**
   * Show Home Page of breeder
   *
   * @param  Request $request
   * @return View
   */
    public function index(Request $request)
    {
        //$users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->where('role_user.role_id','!=',1)->where('users.email_verified','=', 1)->get();
        $all = $this->userCount();
        $breeders = $this->breederCount();
        $customers = $this->customerCount();
        $pending = $this->pendingUserCount();
        $blocked = $this->blockedUserCount();
        $summary = array($all, $breeders, $customers, $pending, $blocked);

        return view(('user.admin.home'), compact('summary'));
    }

    public function displayAllUsers(){
      // $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->
      //         join('roles', 'role_user.role_id','=','roles.id')->
      //         where('role_user.role_id','!=',1)->
      //         where('users.email_verified','=', 1)->get();

      $users = $this->retrieveAllUsers();
      $userArray = [];
      foreach ($users as $user) {
        if($user->role_id!=1 && $user->email_verified==1 && $user->deleted_at==NULL){
          $user->title = ucfirst($user->title);
          $user->token = csrf_token();
          $userArray[] = $user;
        }
      }
      return $userArray;
    }

    public function displayApprovedBreeders(){
      //$users =DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->where('roles.id','=', 2)->where('users.email_verified','=', 1)->get();
      // foreach ($users as $user) {
      //   $user->title = ucfirst($user->title);
      // }
      // return $users;
      $users = $this->retrieveAllUsers();
      $userArray = [];
      foreach ($users as $user) {
        if($user->role_id==2 && $user->email_verified==1 && $user->deleted_at==NULL){
          $user->title = ucfirst($user->title);
          $user->token = csrf_token();
          $userArray[] = $user;
        }
      }
      return $userArray;
    }

    public function displayApprovedCustomer(){
      // $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')->where('roles.id','=', 3)->where('users.email_verified','=', 1)->get();
      // foreach ($users as $user) {
      //   $user->title = ucfirst($user->title);
      // }
      // return $users;
      $users = $this->retrieveAllUsers();
      $userArray = [];
      foreach ($users as $user) {
        if($user->role_id==3 && $user->email_verified==1 && $user->deleted_at==NULL){
          $user->title = ucfirst($user->title);
          $user->token = csrf_token();
          $userArray[] = $user;
        }
      }

      return $userArray;
    }

    public function displayPendingUsers(){
      $users = $this->retrieveAllUsers();
      $userArray = [];
      foreach ($users as $user) {
        if($user->role_id==2 && $user->email_verified==0 && $user->deleted_at==NULL && $user->approved==0){
          $user->title = ucfirst($user->title);
          $user->token = csrf_token();
          $userArray[] = $user;
        }
      }

      return $userArray;
    }

    public function deleteUser(Request $request){
      $user = User::find($request->userId);
      $user->delete();
      return "OK";
    }

    public function displayBlockedUsers(){
      $blockedUsers = DB::table('users')
                      ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                      ->join('roles', 'role_user.role_id','=','roles.id')
                      ->where('role_user.role_id','!=',1)
                      ->where('users.email_verified','=', 1)
                      ->where('users.deleted_at','=', NULL )
                      ->where('users.is_blocked','=', 1 )
                      ->get();
      foreach ($blockedUsers as $blockedUser) {
        $blockedUser->title = ucfirst($blockedUser->title);
        $blockedUser->token = csrf_token();
      }
      return $blockedUsers;
    }

    public function blockUser(Request $request){
      $user = User::find($request->userId);
      $user->is_blocked = !$user->is_blocked;
      $user->save();

      return  "Ok";
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data, $verCode, $password)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($password),
            'verification_code' => $verCode

        ]);
    }
     /**
      * Handle a registration request for the application.
      * Override default register method
      *
      * @param  \Illuminate\Http\Request  $request
      * @return View
      */
     public function register(Request $request)
     {

        $verCode = str_random('10');
        $password = $this->generatePassword();
        $user = $this->create($request->all(), $verCode, $password);
        $user->assignRole('breeder');
        $breeder = Breeder::create([])->users()->save($user);
      //   $data = [
      //      'email' => $request->input('email'),
      //      'verCode' => $verCode,
      //      'type' => 'sent'
      //   ];

         // Mail::send('emails.verification', $data, function ($message) use($data){
         //     $message->to($data['email'])->subject('Breeder Credential for Swine E-Commerce PH');
         // });
         //
         // return view('emails.message', $data);
         //return Redirect::back()->withMessage('User Created!');

     }



    public function generatePassword(){
      $password = str_random(10);;
      return $password;
    }

    public function searchUser(Request $request){
      $search = '%'.$request->search.'%';
      $query = DB::table('users')
                      ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                      ->join('roles', 'role_user.role_id','=','roles.id')
                      ->where('role_user.role_id','!=',1)
                      ->where('users.email_verified','=', 1)
                      ->where('users.deleted_at','=', NULL)
                      ->where('name', 'like', $search)
                      ->get();
      return $query;
    }

}
