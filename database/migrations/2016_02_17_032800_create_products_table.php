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
        /** 
         * name `
         * type (boar, gilt, sow, semen) `
         * min price `
         * max price `
         * breed type `
         * birth date `
         * birth weight `
         * farm from `
         * house type `
         * adg `
         * fcr `
         * bft `
         * lsba `
         * left teats `
         * right teats `
         * other details `
         * 
         * added fields:
         * - house type `
         * - min price `
         * - max price `
         * - birth weight `
         * - lsba `
         * - left teats `
         * - right teats `
        */

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('breeder_id')->unsigned();
            $table->integer('farm_from_id')->unsigned();
            $table->integer('primary_img_id')->unsigned();
            $table->string('name');
            $table->string('type');
            $table->date('birthdate');
            $table->float('birthweight')->nullable();
            $table->integer('breed_id');
            
            // $table->float('price')->nullable();
            $table->float('min_price')->nullable();
            $table->float('max_price')->nullable();

            $table->string('house_type')->nullable();

            $table->integer('quantity')->nullable();
            $table->float('adg')->nullable();
            $table->float('fcr')->nullable();
            $table->float('backfat_thickness')->nullable();

            $table->integer('lsba')->nullable();

            $table->integer('left_teats')->nullable();
            $table->integer('right_teats')->nullable();

            $table->boolean('is_unique')->default(0);

            $table->text('other_details')->nullable();
            $table->enum('status',
                ['hidden', 'displayed', 'requested']
                )->default('hidden');
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
        Schema::drop('products');
    }
}
