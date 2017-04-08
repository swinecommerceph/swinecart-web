<?php

namespace App\Providers;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use DB;
use Carbon\Carbon;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->adminGetMinMaxYearUserCreation();
        $this->getTimeNow();
        $this->getHomeImages();
        $this->spectatorGetMinMaxProductValues();
        $this->getTransactionFirstLastYear();
        $this->getOnlineUsers();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /*
     * Get the last and latest year of active users in the whole database
     *
     * @param none
     * @return array of Carbon parsed
     *
     */
    public function adminGetMinMaxYearUserCreation(){
        view()->composer([
            'user.admin.statisticsBreederActive', 'user.admin.statisticsBreederBlocked', 'user.admin.statisticsBreederDeleted',
            'user.admin.statisticsCustomerActive', 'user.admin.statisticsCustomerBlocked', 'user.admin.statisticsCustomerDeleted',
            'user.admin.averageStatistics','user.spectator.activeBreederStatistics', 'user.spectator.blockedBreederStatistics',
            'user.spectator.deletedBreederStatistics','user.spectator.activeCustomerStatistics', 'user.spectator.blockedCustomerStatistics',
            'user.spectator.deletedCustomerStatistics',
            ], function($view){
            $first =  DB::table('users')->orderBy('created_at', 'asc')->first();
            $last =  DB::table('users')->orderBy('created_at', 'desc')->first();
            $data = [Carbon::parse($first->created_at)->year, Carbon::parse($last->created_at)->year, floor((Carbon::parse($first->created_at)->year + Carbon::parse($last->created_at)->year)/2)];
            $view->with('yearMinMax', $data);
        });
    }


    /*
     * Get the minimum and maximum product information values for spectator products view
     *
     * @param none
     * @return collection
     *
     */
    public function spectatorGetMinMaxProductValues(){
        view()->composer('user.spectator.products', function($view){
            $minmax = DB::table('products')->select(DB::raw('min(price) minprice, max(price) maxprice, min(quantity) minquantity,
                                                        max(quantity) maxquantity, min(adg) minadg, max(adg) maxadg, min(fcr) minfcr, max(fcr) maxfcr,
                                                        min(backfat_thickness) minbfat, max(backfat_thickness) maxbfat'))
                                            ->first();
            $view->with('minmax', $minmax);
        });

    }

    /*
     * Get the current date
     *
     * @param none
     * @return array of Carbon parsed
     *
     */
    public function getTimeNow(){
        view()->composer('user.admin.*', function($view){
            $date = Carbon::now();
            $data = [$date->month, $date->day, $date->year];
            $view->with('now', $data);
        });
    }

    /*
     * Get the oldest and recent year of completed transaction
     *
     * @param none
     * @return array of string
     *
     */
    public function getTransactionFirstLastYear(){
        view()->composer('user.admin.statisticsTotalTransaction', function($view){
            $transactions =  DB::table('transaction_logs')
                                ->where('status', '=', 'sold')
                                ->select(DB::raw('YEAR(created_at) year, COUNT(*) count'))
                                ->groupBy('year')
                                ->orderBy('year', 'desc')
                                ->get();
            $minyear = $transactions->last()->year;
            $maxyear = $transactions->first()->year;
            $midyear = ($minyear+$maxyear)/2;
            $data = [$minyear, $maxyear, $midyear];
            $view->with('minmaxyear', $data);
        });
    }

    /*
     * Get the images in the homepage view of the Customer and Breeder pages
     *
     * @param none
     * @return collection of images
     *
     */
    public function getHomeImages(){
        view()->composer(['user.breeder.home','user.customer.home'],function($view){
            $homeContent = DB::table('home_images')->get();
            $view->with('homeContent', $homeContent);

        });
    }

    public function getOnlineUsers(){
        view()->composer('user.admin.*',function($view){
            $users = User::all();
            $counter = 0;
            foreach ($users as $user) {
                if($user->isOnline()) {
                    $counter++;
                }
            }
            $view->with('counter', $counter);
        });
    }
}
