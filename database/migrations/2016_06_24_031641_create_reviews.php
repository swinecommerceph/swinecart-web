<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('reviews', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('breeder_id')->unsigned();
          $table->string('comment');
          $table->integer('rating_delivery');
          $table->integer('rating_transaction');
          $table->integer('rating_productQuality');
          $table->integer('rating_afterSales');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reviews');
    }
}
