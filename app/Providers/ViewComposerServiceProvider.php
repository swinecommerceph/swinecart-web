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

    public function getTimeNow(){
        view()->composer('user.admin.*', function($view){
            $date = Carbon::now();
            $data = [$date->month, $date->day, $date->year];
            $view->with('now', $data);
        });
    }

    public function getHomeImages(){
        view()->composer('*',function($view){
            $homeContent = DB::table('home_images')->get();
            $view->with('homeContent', $homeContent);

        });
    }
}
