<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreederUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breeder_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('office_address');
            $table->string('office_landline')->nullable();
            $table->string('office_mobile');
            $table->string('farm_address');
            $table->string('farm_type');
            $table->string('farm_landline')->nullable();
            $table->string('farm_mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('breeder_user');
    }
}
