<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministratorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrator_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned();
            $table->string('admin_name');
            $table->string('action');
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
        Schema::drop('administrator_logs');
    }
}
