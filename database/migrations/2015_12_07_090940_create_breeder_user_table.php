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
            $table->integer('logo_img_id')->unsigned();
            $table->string('officeAddress_addressLine1')->nullable();
            $table->string('officeAddress_addressLine2')->nullable();
            $table->string('officeAddress_province')->nullable();
            $table->string('officeAddress_zipCode')->nullable();
            $table->string('office_landline')->nullable();
            $table->string('office_mobile')->nullable();
            $table->string('website')->nullable();
            $table->string('produce')->nullable();
            $table->string('contactPerson_name')->nullable();
            $table->string('contactPerson_mobile')->nullable();
            $table->string('status_instance')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('breeder_user');
    }
}
