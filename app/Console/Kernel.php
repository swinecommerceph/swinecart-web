<?php

namespace App\Console;

use App\Models\Product;
use App\Models\User;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\WSBreederDashboardServer;
use App\Console\Commands\BreederAccreditationNotification;
use App\Console\Commands\ProductNotification;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Mail\SwineCartProductNotification;

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
        \App\Console\Commands\WSChatServer::class,
        \App\Console\Commands\BreederAccreditationNotification::class,
        \App\Console\Commands\ProductNotification::class
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
        $schedule->command('breeder:accreditationexpire')->daily();
        $schedule->command('product:notification')->daily();
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
