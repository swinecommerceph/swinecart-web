<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('swineCart_id')->unsigned();
            $table->json('product_details');
            $table->dateTime('requested')->nullable();
            $table->dateTime('reserved')->nullable();
            $table->dateTime('on_delivery')->nullable();
            $table->dateTime('paid')->nullable();
            $table->dateTime('sold')->nullable();
            $table->dateTime('rated')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_logs');
    }
}
