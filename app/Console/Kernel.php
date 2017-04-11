<?php

namespace App\Console;

use App\Models\Product;
use App\Models\User;

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
                                ->select('breeder_user.id as breeder_id', 'users.name as username' ,'email', 'latest_accreditation', 'status_instance')
                                ->where('status_instance','=','active')
                                ->where('latest_accreditation','=',$expirationAlert1)
                                ->get();

            $breedersGroup2 = DB::table('users')->join('role_user', 'users.id', '=' , 'role_user.user_id')
                                ->join('roles', 'role_user.role_id','=','roles.id')
                                ->where('role_id','=', 2)
                                ->join('breeder_user','breeder_user.id', '=', 'users.userable_id')
                                ->select('breeder_user.id as breeder_id', 'users.name as username', 'email', 'latest_accreditation', 'status_instance')
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

            foreach ($dateNeeded as $date) {
                $type = 0;
                $product = Product::where('id', $date->product_id)->first()->name;
                $user = User::where('userable_type', 'App\Models\Breeder')->where('userable_id', $date->breeder_id)->first();
                $time = Carbon::now()->addMinutes(10);
                $date->date_needed = Carbon::parse($date->date_needed)->format('l jS \\of F Y h:i:s A');
                Mail::to($user->email)
                    ->later($time, new SwineCartProductNotification($type, $user, $product, $date));
            }
            foreach ($reservation as $date) {
                $type = 1;
                $product = Product::where('id', $date->product_id)->first()->name;
                $user = User::where('userable_type', 'App\Models\Customer')->where('userable_id', $date->customer_id)->first();
                $time = Carbon::now()->addMinutes(10);
                $date->expiration_date = Carbon::parse($date->expiration_date)->format('l jS \\of F Y h:i:s A');
                Mail::to($user->email)
                    ->later($time, new SwineCartProductNotification($type, $user, $product, $date));
            }

            foreach ($breedersGroup1 as $breederGr1) {
                $type = 0;
                $time = Carbon::now()->addMinutes(10);
                $expiration = Carbon::parse($breederGr1->latest_accreditation)->addYear()->format('l jS \\of F Y h:i:s A');
                Mail::to($breederGr2->email)
                    ->later($time, new SwineCartBreederAccreditationExpiration($type, $breederGr1->username, $breederGr1->email, $expiration));
            }
            foreach ($breedersGroup2 as $breederGr2) {
                $type = 1;
                $time = Carbon::now()->addMinutes(10);
                $expiration = Carbon::parse($breederGr2->latest_accreditation)->addYear()->format('l jS \\of F Y h:i:s A');
                Mail::to($breederGr2->email)
                    ->later($time, new SwineCartBreederAccreditationExpiration($type, $breederGr2->username, $breederGr2->email, $expiration));
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
