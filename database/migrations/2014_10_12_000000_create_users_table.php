<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->integer('userable_id')->unsigned();
            $table->string('userable_type');
            $table->string('delete_reason')->nullable();
            $table->string('block_reason')->nullable();
            $table->integer('block_frequency')->unsigned();
            $table->boolean('update_profile')->default('1');
            $table->string('verification_code');
            $table->boolean('email_verified')->default('0');
            $table->dateTime('blocked_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
