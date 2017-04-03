<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\WSBreederDashboardServer;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\WSBreederDashboardServer::class,
        \App\Console\Commands\WSChatServer::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // schedule a mail notification daily at 12 midnight to breeders regarding accreditation expiration (month before and week before)
        $schedule->call(function () {
            $expirationAlert1 = Carbon::now()->subYear(1)->subMonth(1)->toDateString();
            $expirationAlert2 = Carbon::now()->subYear(1)->subDay(7)->toDateString();

            $breedersGroup1 = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                                ->join('roles', 'role_user.role_id','=','roles.id')
                                ->where('role_id','=', 2)
                                ->join('breeder_user','breeder_user.id', '=', 'users.userable_id')
                                ->select('breeder_user.id as breeder_id','email', 'latest_accreditation', 'status_instance')
                                ->where('status_instance','=','active')
                                ->where('latest_accreditation','=',$expirationAlert1)
                                ->get();
            $breedersGroup2 = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                                ->join('roles', 'role_user.role_id','=','roles.id')
                                ->where('role_id','=', 2)
                                ->join('breeder_user','breeder_user.id', '=', 'users.userable_id')
                                ->select('breeder_user.id as breeder_id','email', 'latest_accreditation', 'status_instance')
                                ->where('status_instance','=','active')
                                ->where('latest_accreditation','=',$expirationAlert2)
                                ->get();

            $dateNeeded = DB::table('swine_cart_items')
                        ->join('transaction_logs', 'transaction_logs.product_id','=','swine_cart_items.product_id')
                        ->whereMonth('date_needed', Carbon::now()->month)
                        ->whereYear('date_needed',  Carbon::now()->year)
                        ->whereDay('date_needed', Carbon::now()->day-1)
                        ->get();

            $reservation = DB::table('product_reservations')
                        ->whereMonth('expiration_date', Carbon::now()->month)
                        ->whereYear('expiration_date',  Carbon::now()->year)
                        ->whereDay('expiration_date', Carbon::now()->day-1)
                        ->get();

            $breeders = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->join('roles', 'role_user.role_id','=','roles.id')
                        ->where('role_id','=', 2)
                        ->get();
            $customers = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                        ->join('roles', 'role_user.role_id','=','roles.id')
                        ->where('role_id','=', 3)
                        ->get();
            foreach ($dateNeeded as $date) {
                Mail::send('emails.notification', ['type'=>'dateNeeded'], function ($message) use($breeders){
                  $message->to($breeders->where('userable_id', $date->breeder_id)->email)->subject('Swine E-Commerce PH: Account Notification');
                });
            }
            foreach ($reservation as $date) {
                Mail::send('emails.notification', ['type'=>'productExpiration'], function ($message) use($customers){
                  $message->to($customers->where('userable_id', $date->customer_id)->email)->subject('Swine E-Commerce PH: Account Notification');
                });
            }
            foreach ($breedersGroup1 as $breederGr1) {
                Mail::send('emails.notification', ['type'=>'expirationMonth'], function ($message) use($breederGr1){
                  $message->to($breederGr1->email)->subject('Swine E-Commerce PH: Account Notification');
                });
            }
            foreach ($breedersGroup2 as $breederGr2) {
                Mail::send('emails.notification', ['type'=>'expirationWeek'], function ($message) use($breederGr2){
                  $message->to($breederGr2->email)->subject('Swine E-Commerce PH: Account Notification');
                });
            }
        })->dailyAt("00:00");

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
