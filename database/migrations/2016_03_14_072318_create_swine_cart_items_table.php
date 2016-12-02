<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSwineCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swine_cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('reservation_id')->unsigned()->default('0');
            $table->integer('quantity');
            $table->boolean('if_requested')->default('0');
            $table->boolean('if_rated')->default('0');
            $table->date('date_needed');
            $table->mediumText('special_request');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('swine_cart_items');
    }
}
