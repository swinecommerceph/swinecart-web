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
            $table->string('officeAddress_addressLine1');
            $table->string('officeAddress_addressLine2');
            $table->string('officeAddress_province');
            $table->string('officeAddress_zipCode');
            $table->string('office_landline')->nullable();
            $table->string('office_mobile');
            $table->string('website');
            $table->string('produce');
            $table->string('contactPerson_name');
            $table->string('contactPerson_mobile');
            $table->date('latest_accreditation');
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
