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
use App\Models\HomeImage;
use App\Models\AdministratorLog;
use App\Repositories\AdminRepository;

use Mail;
use DB;
use Auth;
use Input;
use Storage;
use File;
use Carbon\Carbon;

class AdminController extends Controller
{
    protected $user;

    /**
    * Create new BreederController instance
    */
    public function __construct()
    {
      $this->middleware('role:admin');
      $this->middleware(function($request, $next){
          $this->user = Auth::user();

          return $next($request);
      });
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
                        ->where('users.blocked_at','=', NULL)
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
                        ->where('users.blocked_at','=', NULL)
                        ->where('users.deleted_at','=', NULL)
                        ->count();
        return $count;
    }

    /**
     * Helper functions for retrieving the number of pending breeders
     *
     * @param  none
     * @return count of all users that are pending
     */
    public function pendingUserCount(){
        $count = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->join('roles', 'role_user.role_id','=','roles.id')
                        ->where('role_user.role_id','=',2)
                        ->where('users.approved_at','=', NULL)
                        ->where('users.deleted_at','=', NULL)
                        ->count();
        return $count;
    }

    /**
     * Helper functions for retrieving the number of blocked users
     *
     * @param  none
     * @return count of all users that are blocked
     */
    public function blockedUserCount(){
        $count = DB::table('users')
                        ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->join('roles', 'role_user.role_id','=','roles.id')
                        ->where('role_user.role_id','!=',1)
                        ->where('users.email_verified','=', 1)
                        ->whereNotNull('users.blocked_at')
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
        $all = $this->userCount();
        $breeders = $this->breederCount();
        $customers = $this->customerCount();
        $pending = $this->pendingUserCount();
        $blocked = $this->blockedUserCount();
        $summary = array($all, $breeders, $customers, $pending, $blocked);

        return view(('user.admin.home'), compact('summary'));
    }

    /**
     * Function to get all users in the database that are not deleted and blocked
     *
     * @param  none
     * @return array of user
     */
    public function displayAllUsers(){
        $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')
                ->whereNotNull('approved_at')
                ->whereNull('deleted_at')
                ->paginate(10);

        return view('user.admin._displayUsers',compact('users'));
    }

    /**
     * Function to get all approved breeders
     *
     * @param  none
     * @return array of breeders
     */
    public function displayApprovedBreeders(){
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

    /**
     * Function to get all approved customers (not used in the website yet)
     *
     * @param  none
     * @return array of customers
     */
    public function displayApprovedCustomer(){
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

    /**
     * Function to get all pending users
     *
     * @param  none
     * @return array of pending users
     */
    public function displayPendingUsers(){
        $users = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')->join('roles', 'role_user.role_id','=','roles.id')
                    ->where('role_id','=',2)
                    ->where('approved_at','=',NULL)
                    ->whereNull('deleted_at')
                    ->paginate(10);
        return view('user.admin._pendingUsers',compact('users'));
    }

    /**
     * Function to delete a user (triggers soft delete)
     *
     * @param  none
     * @return OK (string) status
     */
    public function deleteUser(Request $request){
        $adminID = Auth::user()->id;
        $adminName = Auth::user()->name;
        $user = User::find($request->id);           // find the user
        $user->delete();                                // delete it in the database
        // create a AdministratorLog entry for the action done
        AdministratorLog::create([
            'admin_id' => $adminID,
            'admin_name' => $adminName,
            'user' => $user->name,
            'category' => 'Delete',
            'action' => 'Deleted '.$user->name,
        ]);

        // send an email notification to the user's email
        Mail::send('emails.notification', ['type'=>'deleted', 'approved'=>$user->approved_at], function ($message) use($user){
          $message->to($user->email)->subject('Swine E-Commerce PH: Account Notification');
        });

        return Redirect::back()->with('message','User Deleted');
    }

    /**
     * Function to get the blocked user list
     *
     * @param  none
     * @return array of blocked users
     */
    public function displayBlockedUsers(){
        // get all the user with the blocked status
        $users = DB::table('users')
                      ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                      ->join('roles', 'role_user.role_id','=','roles.id')
                      ->where('role_user.role_id','!=',1)
                      ->where('users.email_verified','=', 1)
                      ->where('users.deleted_at','=', NULL )
                      ->whereNotNull('users.blocked_at')
                      ->paginate(10);
        // foreach ($blockedUsers as $blockedUser) {
        //     $blockedUser->title = ucfirst($blockedUser->title);     // fix the format for the role in each of the users queried
        //     $blockedUser->token = csrf_token();                     // get the token
        // }
        return view('user.admin._blockedUsers',compact('users'));
    }

    /**
     * Function to get the searched approved user
     *
     * @param  request array from search form containing string for name and values for checkboxes
     * @return array of approved users that match the search criterion
     */
    public function searchUser(Request $request){
        //$values = [$request->admin, $request->breeder, $request->customer];
        if($request->breeder==null && $request->customer==null){
            $users = DB::table('users')
                          ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                          ->join('roles', 'role_user.role_id','=','roles.id')
                          ->where('users.name','LIKE', "%$request->search%")
                          ->where('users.email_verified','=', 1)
                          ->where('users.deleted_at','=', NULL )
                          ->paginate(10);
        }else{
            $values = [$request->breeder, $request->customer];
            $search = [];
            for($i = 0; $i < 2; $i++){
                if($values[$i] != null){
                    $search[] = $values[$i];
                }
            }
            $users = DB::table('users')
                          ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                          ->join('roles', 'role_user.role_id','=','roles.id')
                          ->where('users.name','LIKE', "%$request->search%")
                          ->where('users.email_verified','=', 1)
                          ->where('users.deleted_at','=', NULL )
                          ->whereIn('role_user.role_id', $search)
                          ->paginate(10);
        }


        return view('user.admin._displayUsers',compact('users'));
    }

    /**
     * Function to get the searched pending users
     *
     * @param  request array from search form containing string for name and values for checkboxes
     * @return array of pending users that match the search criterion
     */
    public function searchPendingUser(Request $request){
        $users = DB::table('users')
                      ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                      ->join('roles', 'role_user.role_id','=','roles.id')
                      ->where('users.name','LIKE', "%$request->search%")
                      ->where('users.email_verified','=', 0)
                      ->where('users.deleted_at','=', NULL )
                      ->paginate(10);

        return view('user.admin._pendingUsers',compact('users'));
    }

    public function searchBlockedUsers(Request $request){
        if($request->breeder==null && $request->customer==null){
            $users = DB::table('users')
                          ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                          ->join('roles', 'role_user.role_id','=','roles.id')
                          ->where('users.name','LIKE', "%$request->search%")
                          ->where('users.email_verified','=', 1)
                          ->where('users.deleted_at','=', NULL )
                          ->whereNotNull('users.blocked_at')
                          ->paginate(10);
        }else{
            $values = [$request->breeder, $request->customer];
            $search = [];
            for($i = 0; $i < 2; $i++){
                if($values[$i] != null){
                    $search[] = $values[$i];
                }
            }
            $users = DB::table('users')
                          ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                          ->join('roles', 'role_user.role_id','=','roles.id')
                          ->where('users.name','LIKE', "%$request->search%")
                          ->where('users.email_verified','=', 1)
                          ->where('users.deleted_at','=', NULL )
                          ->whereNotNull('users.blocked_at')
                          ->whereIn('role_user.role_id', $search)
                          ->paginate(10);
        }


        return view('user.admin._blockedUsers',compact('users'));
    }



    /**
     * Function to add the user to the blocked list, changes blocked status to 1
     *
     * @param  none
     * @return status string "OK"
     */
    public function blockUser(Request $request){
        $adminID = Auth::user()->id;
        $adminName = Auth::user()->name;
        $user = User::find($request->id);       // find the user using the user id
        $user->blocked_at = Carbon::now();     // change the status for is_blocked column
        $user->save();                              // save the change to the database
        // create a log entry for the action done
        if($user->blocked_at!=NULL){
            AdministratorLog::create([
                'admin_id' => $adminID,
                'admin_name' => $adminName,
                'user' => $user->name,
                'category' => 'Block',
                'action' => 'Blocked '.$user->name,
            ]);
        }else{
            AdministratorLog::create([
                'admin_id' => $adminID,
                'admin_name' => $adminName,
                'user' => $user->name,
                'category' => 'Unblock',
                'action' => 'Unblocked '.$user->name,
            ]);
        }
        // send an email notification to the email of the user
        Mail::send('emails.notification', ['type'=>'blocked', 'status'=>$user->blocked_at], function ($message) use($user){
          $message->to($user->email)->subject('Swine E-Commerce PH: Account Notification');
        });
        if($user->blocked_at != NULL){
            return Redirect::back()->with('message','User Blocked');
        }else{
            return Redirect::back()->with('message','User Unblocked');
        }

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
        ]);
    }

    /**
     * Create a new user instance in the database.
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
     * Create an initial account for the breeder
     *
     * @param  \Illuminate\Http\Request  $request
     * @return View
     */
    public function createUser(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
              $request, $validator
            );
        }
        $verCode = str_random('10');        // create a verification code
        $password = $this->generatePassword();  // generate the initial password for the breeder and save it to a variable to get the original password before encryption
        $user = $this->create($request->all(), $verCode, $password); // create a user instance
        $user->assignRole('breeder');       // assign a breeder role to it
        $breeder = Breeder::create([])->users()->save($user);   // create a breeder instance for that user

        // data to be passed in the email
        $data = [
            'email' => $request->input('email'),
            'password' => $password
        ];

        $adminID = Auth::user()->id;
        $adminName = Auth::user()->name;
        // create a log entry for the action done
        AdministratorLog::create([
            'admin_id' => $adminID,
            'admin_name' => $adminName,
            'user' => $data['email'],
            'category' => 'Create',
            'action' => 'Created user account for '.$data['email'],
        ]);

        Mail::send('emails.credentials', ['email' => $request->input('email'),'password' => $password], function ($message) use($data){     // send an email containing the credential of the user to the input email
          $message->to($data['email'])->subject('Breeder Credentials for Swine E-Commerce PH');
        });

        return Redirect::back()->withMessage('User Created!'); // redirect to the page and display a toast notification that a user is created
    }

    /**
     * Generates a random password with a length of 10 characters
     * @return String
     */
    public function generatePassword(){
        $password = str_random(10);;
        return $password;
    }

    /**
     * Accept a pending user request and send an email verification to the user's email
     *
     * @return none (redirect)
     */
    public function acceptUser(Request $request){
        $adminID = Auth::user()->id;
        $adminName = Auth::user()->name;
        $user = User::find($request->id);
        $user->approved_at = Carbon::now();     // negate the status to approve user
        // create a log entry for the action done
        AdministratorLog::create([
            'admin_id' => $adminID,
            'admin_name' => $adminName,
            'user' => $user->name,
            'category' => 'Accept',
            'action' => 'Approved '.$user->name,
        ]);

        // send an email notification to the email of the user
        Mail::send('emails.notification', ['type'=>'accepted'], function ($message) use($user){
          $message->to($user->email)->subject('Swine E-Commerce PH: Account Notification');
        });
        $user->save();  // save changes to the database

        return Redirect::back()->withMessage('User Accepted!'); // redirect to the page and display a toast notification that a user is created
    }

    /**
    * @TODO Separate DELETE and REJECT user function for better notifications
    *
    * Reject a pending user request and send an email verification to the user's email
    *
    * @return none (redirect)
    */
    public function rejectUser(Request $request){
        $adminID = Auth::user()->id;
        $adminName = Auth::user()->name;
        $user = User::find($request->id);
        $user->approved = NULL;     // negate the status to approve user
        // create a log entry for the action done
        AdministratorLog::create([
            'admin_id' => $adminID,
            'admin_name' => $adminName,
            'user' => $user->name,
            'category' => 'Reject',
            'action' => 'Reject '.$user->name,
        ]);
        // send an email notification to the email of the user
        Mail::send('emails.notification', ['type'=>'rejected'], function ($message) use($user){
          $message->to($user->email)->subject('Swine E-Commerce PH: Account Notification');
        });
        $user->save();  // save changes to the database

        return Redirect::back()->withMessage('User Rejected!'); // redirect to the page and display a toast notification that a user is created
    }

    /**
     * Displays form for the registration of breeder
     * @return View
     *
     * @TODO Registration form file checker
     */
    public function getRegistrationForm(){
        return view('user.admin.form');
    }

    /**
     * @todo Submit the registration form for breeder accreditation
     * @return View
     *
     * @TODO Checker for submitted form
     */
    public function submitRegistrationForms(Request $request){
        dd($request);
    }

    /**
     * Displays the administrator logs
     *
     * @param none
     * @return View the administrator logs
     *
     */
    public function getAdministratorLogs(){
        $logs = DB::table('administrator_logs')->paginate(10);
        return view('user.admin._adminLogs',compact('logs'));
    }

    public function searchAdministratorLogs(Request $request){

        if($request->option!=null){
            $logs = DB::table('administrator_logs')
                    ->whereIn('category', $request->option)
                    ->where('user', 'LIKE', "%$request->search%")
                    ->orWhere('admin_name', 'LIKE', "%$request->search%")
                    ->paginate(10);
        }else{
            $logs = DB::table('administrator_logs')
                    ->where('user', 'LIKE', "%$request->search%")
                    ->orWhere('admin_name', 'LIKE', "%$request->search%")
                    ->paginate(10);
        }

        return view('user.admin._adminLogs',compact('logs'));
    }

    /**
     * Check if media is Image depending on extension
     *
     * @param  String   $extension
     * @return Boolean
     */
    private function isImage($extension)
    {
        return ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') ? true : false;
    }

    /**
     * Displays the manage pages view
     *
     * @param none
     * @return View and All content homepage images and texts
     *
     */
    public function manageHomePage(){
        $homeContent = DB::table('home_images')->get();
        return view('user.admin._manageImages',compact('homeContent'));
    }

     /**
      * Add an image and/or text to he home page slider
      *
      * @param form request
      * @return String
      * @todo Error detection
      */
     public function addHomeImage(Request $request){
         if (Input::hasFile('image')) {
            $filename = date('d-m-y-H-i-s',time()).'-'.Input::file('image')->getClientOriginalName();
            Input::file('image')->move(public_path('/images/homeimages/'), $filename);
            $content = new HomeImage;
            $content->text = $request->textContent;
            $content->title = $request->title;
            $content->name = $filename;
            $content->path = '/images/homeimages/';
            $content->save();
            return Redirect::back()->with('message','Image Added');
        } else {
            return Redirect::back()->with('message','Operation Failed');
        }
     }

     /**
      * Delete image and text in the home page
      *
      * @param form request
      * @return String
      * @todo Error detection
      */
     public function deleteContent(Request $request){
         $content = HomeImage::find($request->content_id);
         //File::delete($content->path.$content->name);
         unlink(public_path($content->path.$content->name));
         //dd($content->path.$content->name);
         $content->delete();
         return Redirect::back()->with('message','Image Deleted');
     }

     /**
      * Edit an image and/or text in the home page
      *
      * @param form request
      * @return String
      * @todo Error detection
      */
     public function editContent(Request $request){
         $content = HomeImage::find($request->content_id);
         if (Input::hasFile('image')) {
            unlink(public_path($content->path.$content->name));
            $filename = date('d-m-y-H-i-s',time()).'-'.Input::file('image')->getClientOriginalName();
            Input::file('image')->move(public_path('/images/homeimages/'), $filename);
            $content->name = $filename;
            $content->path = '/images/homeimages/';
        }

        if(!empty($request->title)){
            $content->title = $request->title;
        }

        if(!empty($request->textContent)){
            $content->text = $request->textContent;
        }
        $content->save();
        return Redirect::back()->with('message','Content Edited');
     }

     public function goToUserlist(){
         return redirect()->route('home/userlist');
     }

     public function goToPending(){
         return redirect()->route('home/pending/users');
     }

     /**
      * Edit show the created users statistics per month from current year
      *
      * @param none
      * @return array of counts
      * @todo optimize the method of getting the count of created users per month, change query to filter the year
      */
     public function showStatisticsActiveBreeder(){
         $date = Carbon::now();
         $year = $date->year;
         $month = [
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('1'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('2'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('3'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('4'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('5'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('6'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('7'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('8'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('9'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('10'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('11'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('12'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
         ];

        return view('user.admin.statisticsBreederActive', compact('month', 'year'));
     }

     public function showStatisticsActiveBreederYear(Request $request){
         $year = $request->year;
         $month = [
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('1'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('2'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('3'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('4'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('5'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('6'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('7'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('8'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('9'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('10'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('11'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereNull('blocked_at')
                             ->whereNull('deleted_at')
                             ->whereMonth('approved_at', '=', date('12'))
                             ->whereYear('approved_at', '=', $year)
                             ->count(),
         ];

         return view('user.admin.statisticsBreederActive', compact('month', 'year'));
     }

     /**
      * Edit show the deleted users statistics per month
      *
      * @param none
      * @return array of counts
      * @todo optimize the method of getting the count of deleted users per month, change query to filter the year
      */
     public function showStatisticsDeletedBreeder(){
         $date = Carbon::now();
         $year = $date->year;
         $month = [
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('1'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('2'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('3'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('4'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('5'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('6'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('7'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('8'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('9'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('10'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('11'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('12'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
         ];

        return view('user.admin.statisticsBreederDeleted', compact('month', 'year'));
     }

     public function showStatisticsDeletedBreederYear(Request $request){
         $year = $request->year;
         $month = [
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('1'))
                              ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('2'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('3'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('4'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('5'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('6'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('7'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('8'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('9'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('10'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('11'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',2)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('deleted_at', '=', date('12'))
                             ->whereYear('deleted_at', '=', $year)
                             ->count(),
         ];

         return view('user.admin.statisticsBreederDeleted', compact('month', 'year'));
     }


     /**
      * Edit show the blocked users statistics per month
      *
      * @param none
      * @return array of counts
      * @todo optimize the method of getting the count of blocked users per month, change query to filter the year
      */
      public function showStatisticsBlockedBreeder(){
          $date = Carbon::now();
          $year = $date->year;
          $month = [
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('1'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('2'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('3'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('4'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('5'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('6'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('7'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('8'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('9'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('10'))
                              ->whereYear('created_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('11'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('12'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
          ];
          return view('user.admin.statisticsBreederBlocked', compact('month', 'year'));
      }

      public function showStatisticsBlockedBreederYear(Request $request){
          $year = $request->year;
          $month = [
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('1'))
                               ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('2'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('3'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('4'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('5'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('6'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('7'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
              ->join('roles', 'role_user.role_id','=','roles.id')
              ->where('role_user.role_id','=',2)
              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('8'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('9'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('10'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('11'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
              DB::table('users')
                              ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                              ->join('roles', 'role_user.role_id','=','roles.id')
                              ->where('role_user.role_id','=',2)
                              ->where('users.email_verified','=', 1)
                              ->whereNotNull('approved_at')
                              ->whereMonth('blocked_at', '=', date('12'))
                              ->whereYear('blocked_at', '=', $year)
                              ->count(),
          ];

          return view('user.admin.statisticsBreederBlocked', compact('month', 'year'));
      }

      public function showStatisticsDashboard(){
        $date = Carbon::now();
        $year = $date->year;
        $month = $date->month;
        $yesterday = new Carbon('yesterday');
        $lastMonth = new Carbon('last month');

        $stats = [];

        $deleted = DB::table('users')
                    ->whereMonth('deleted_at', '=', $month)
                    ->whereYear('deleted_at', '=', $year)
                    ->count();
        $blocked = DB::table('users')
                    ->whereMonth('blocked_at', '=', $month)
                    ->whereYear('blocked_at', '=', $year)
                    ->count();
        $new = DB::table('users')
                    ->whereMonth('created_at', '=', $month)
                    ->whereYear('created_at', '=', $year)
                    ->count();
        $adminLogs = DB::table('administrator_logs')
                    ->whereMonth('created_at', '=', $month)
                    ->whereYear('created_at', '=', $year)
                    ->whereDay('created_at', '=', $yesterday)
                    ->orderBy('created_at', 'ASC')
                    ->get();


        foreach ($adminLogs as $logs) {
            $logs->created_at = Carbon::parse($logs->created_at)->toDayDateTimeString();
        }

        $stats = [$deleted, $blocked, $new, $adminLogs];
        return view('user.admin.statisticsDashboard',compact('stats'));
      }


    public function showStatisticsActiveCustomer(){
        $date = Carbon::now();
        $year = $date->year;
        $month = [
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('1'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('2'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('3'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('4'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('5'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('6'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('7'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('8'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('9'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('10'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('11'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('12'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
        ];

       return view('user.admin.statisticsCustomerActive', compact('month', 'year'));
    }

    public function showStatisticsActiveCustomerYear(Request $request){
        $year = $request->year;
        $month = [
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('1'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('2'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('3'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('4'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('5'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('6'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('7'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('8'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('9'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('10'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('11'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereNull('blocked_at')
                            ->whereNull('deleted_at')
                            ->whereMonth('approved_at', '=', date('12'))
                            ->whereYear('approved_at', '=', $year)
                            ->count(),
        ];

        return view('user.admin.statisticsCustomerActive', compact('month', 'year'));
    }

    /**
     * Edit show the deleted users statistics per month
     *
     * @param none
     * @return array of counts
     * @todo optimize the method of getting the count of deleted users per month, change query to filter the year
     */
    public function showStatisticsDeletedCustomer(){
        $date = Carbon::now();
        $year = $date->year;
        $month = [
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('1'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('2'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('3'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('4'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('5'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('6'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('7'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('8'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('9'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('10'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('11'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('12'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
        ];

       return view('user.admin.statisticsCustomerDeleted', compact('month', 'year'));
    }

    public function showStatisticsDeletedCustomerYear(Request $request){
        $year = $request->year;
        $month = [
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('1'))
                             ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('2'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('3'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('4'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('5'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('6'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('7'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('8'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('9'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('10'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('11'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
            DB::table('users')
                            ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                            ->join('roles', 'role_user.role_id','=','roles.id')
                            ->where('role_user.role_id','=',3)
                            ->where('users.email_verified','=', 1)
                            ->whereNotNull('approved_at')
                            ->whereMonth('deleted_at', '=', date('12'))
                            ->whereYear('deleted_at', '=', $year)
                            ->count(),
        ];

        return view('user.admin.statisticsCustomerDeleted', compact('month', 'year'));
    }


    /**
     * Edit show the blocked users statistics per month
     *
     * @param none
     * @return array of counts
     * @todo optimize the method of getting the count of blocked users per month, change query to filter the year
     */
     public function showStatisticsBlockedCustomer(){
         $date = Carbon::now();
         $year = $date->year;
         $month = [
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('1'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('2'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('3'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('4'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('5'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('6'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('7'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('8'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('9'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('10'))
                             ->whereYear('created_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('11'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('12'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
         ];
         return view('user.admin.statisticsCustomerBlocked', compact('month', 'year'));
     }

     public function showStatisticsBlockedCustomerYear(Request $request){
         $year = $request->year;
         $month = [
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('1'))
                              ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('2'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('3'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('4'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('5'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('6'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('7'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('8'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('9'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('10'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('11'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
             DB::table('users')
                             ->join('role_user', 'users.id', '=' , 'role_user.user_id')
                             ->join('roles', 'role_user.role_id','=','roles.id')
                             ->where('role_user.role_id','=',3)
                             ->where('users.email_verified','=', 1)
                             ->whereNotNull('approved_at')
                             ->whereMonth('blocked_at', '=', date('12'))
                             ->whereYear('blocked_at', '=', $year)
                             ->count(),
         ];

         return view('user.admin.statisticsCustomerBlocked', compact('month', 'year'));
     }

     public function showStatisticsTimeline(){
         $date = Carbon::now();
         $year = $date->year;
         $month = $date->month;
         $day = $date->day;
         $adminLogs = DB::table('administrator_logs')
                     ->whereDay('created_at', '=', $day)
                     ->whereMonth('created_at', '=', $month)
                     ->whereYear('created_at', '=', $year)
                     ->get();

         foreach ($adminLogs as $logs) {
             $logs->created_at = Carbon::parse($logs->created_at)->toTimeString();
         }

         $dateNow = $date->toFormattedDateString();
         return view('user.admin.statisticsTimeline', compact('dateNow','adminLogs'));
     }

     public function showStatisticsTimelineDate(Request $request){
         $date = new Carbon($request->date);
         // dd($date);
         $year = $date->year;
         $month = $date->month;
         $day = $date->day;
         $adminLogs = DB::table('administrator_logs')
                     ->whereDay('created_at', '=', $day)
                     ->whereMonth('created_at', '=', $month)
                     ->whereYear('created_at', '=', $year)
                     ->get();

         foreach ($adminLogs as $logs) {
             $logs->created_at = Carbon::parse($logs->created_at)->toTimeString();
         }
         $dateNow = $date->toFormattedDateString();
         return view('user.admin.statisticsTimeline', compact('dateNow','adminLogs'));
     }


}
