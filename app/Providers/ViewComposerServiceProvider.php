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
        view()->composer('user.admin.statistics', function($view){
            $first =  DB::table('users')->orderBy('created_at', 'asc')->first();
            $last =  DB::table('users')->orderBy('created_at', 'desc')->first();

            $data = [Carbon::parse($first->created_at)->year, Carbon::parse($last->created_at)->year];
            $view->with('yearMinMax', $data);
        });
    }

    public function spectatorGetMinMaxProductCost(){

    }


}
