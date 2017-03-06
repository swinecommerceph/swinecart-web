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
        view()->composer('*', function($view){
            $first =  DB::table('users')->orderBy('created_at', 'asc')->first();
            $last =  DB::table('users')->orderBy('created_at', 'desc')->first();

            $data = [Carbon::parse($first->created_at)->year, Carbon::parse($last->created_at)->year];
            $view->with('yearMinMax', $data);
        });
    }
    

    public function spectatorGetMinMaxProductCost(){

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
     * Get the images in the homepage view of the Customer and Breeder pages
     *
     * @param none
     * @return collection of images
     *
     */
    public function getHomeImages(){
        view()->composer('*',function($view){
            $homeContent = DB::table('home_images')->get();
            $view->with('homeContent', $homeContent);

        });
    }
}
