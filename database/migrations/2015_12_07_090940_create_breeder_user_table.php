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
            $table->string('officeAddress_addressLine1');
            $table->string('officeAddress_addressLine2');
            $table->string('officeAddress_province');
            $table->string('officeAddress_zipCode');
            $table->string('office_landline')->nullable();
            $table->string('office_mobile');
            $table->string('farmAddress_addressLine1');
            $table->string('farmAddress_addressLine2');
            $table->string('farmAddress_province');
            $table->string('farmAddress_zipCode');
            $table->string('farm_type');
            $table->string('farm_landline')->nullable();
            $table->string('farm_mobile');
            $table->string('contactPerson_name');
            $table->string('contactPerson_mobile');
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
