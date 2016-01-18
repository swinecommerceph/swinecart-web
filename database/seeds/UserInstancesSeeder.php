<?php

use Illuminate\Database\Seeder;

class UserInstancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// For Customers
        factory(App\Models\User::class, 3)->create()->each(function($user){
        	$user->assign('customer');
        	// $profile = factory(App\Models\Customer::class)->create();
        	// $profile->users()->save($user);
        });

        // For Breeders
        factory(App\Models\User::class, 3)->create()->each(function($user){
         	$user->assign('breeder');
         	// $profile = factory(App\Models\Customer::class)->create();
         	// $profile->users()->save($user);
        });
    }
}
