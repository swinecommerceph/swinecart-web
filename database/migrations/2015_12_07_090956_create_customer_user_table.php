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
            $table->string('address_addressLine1');
            $table->string('address_addressLine2');
            $table->string('address_province');
            $table->string('address_zipCode');
            $table->string('landline')->nullable();
            $table->string('mobile');
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
        Schema::dropIfExists('customer_user');
    }
}
