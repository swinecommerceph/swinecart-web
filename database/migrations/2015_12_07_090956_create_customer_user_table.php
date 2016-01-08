<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address');
            $table->string('landline')->nullable();
            $table->string('mobile');
            $table->string('farm_address')->nullable();
            $table->string('farm_type')->nullable();
            $table->string('farm_landline')->nullable();
            $table->string('farm_mobile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_user');
    }
}
