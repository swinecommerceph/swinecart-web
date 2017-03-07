<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'breeder','customer', 'spectator'];
        foreach ($roles as $role) {
        	$roleInstance = new App\Models\Role;
        	$roleInstance->title = $role;
        	$roleInstance->save();
        }

    }
}
