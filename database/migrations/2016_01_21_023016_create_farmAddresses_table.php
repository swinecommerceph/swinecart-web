<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFarmAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('addressLine1');
            $table->string('addressLine2');
            $table->string('province');
            $table->string('zipCode');
            $table->string('farmType');
            $table->string('landline')->nullable();
            $table->string('mobile');
            $table->integer('addressable_id')->unsigned();
            $table->string('addressable_type');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farm_addresses');
    }
}
