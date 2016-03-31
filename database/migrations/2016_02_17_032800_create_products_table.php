<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('breeder_id')->unsigned();
            $table->integer('farm_from_id')->unsigned();
            $table->integer('primary_img_id')->unsigned();
            $table->string('name');
            $table->string('type');
            $table->integer('age');
            $table->integer('breed_id');
            $table->float('price');
            $table->integer('quantity');
            $table->integer('adg');
            $table->float('fcr');
            $table->float('backfat_thickness');
            $table->text('other_details');
            $table->string('status')->default('showcase');
            $table->string('status_instance')->default('active');
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
        Schema::drop('products');
    }
}
