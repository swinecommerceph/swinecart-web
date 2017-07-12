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
            $table->string('name')->nullable();
            $table->string('addressLine1')->nullable();
            $table->string('addressLine2')->nullable();
            $table->string('province')->nullable();
            $table->string('zipCode')->nullable();
            $table->string('farmType')->nullable();
            $table->string('landline')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('addressable_id')->unsigned()->nullable();
            $table->string('addressable_type')->nullable();
            $table->string('accreditation_no')->nullable();
            $table->enum('accreditation_status', ['active', 'inactive', 'not_applicable'])->default('not_applicable');
            $table->date('accreditation_date')->nullable();
            $table->date('accreditation_expiry')->nullable();
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
