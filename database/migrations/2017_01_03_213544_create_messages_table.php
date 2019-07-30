<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('breeder_id')->unsigned();
            $table->integer('admin_id')->unsigned();
            $table->mediumText('message');
            $table->string('media_url')->nullable();
            $table->enum('media_type', [NULL, 'photo', 'video'])->default(NULL);
            $table->boolean('direction')->default(0); //0 from customer | 1 from breeder | 2 from admin
            $table->datetime('read_at')->nullable();
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
        Schema::dropIfExists('messages');
    }
}
