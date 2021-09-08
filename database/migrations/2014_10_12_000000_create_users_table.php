<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id')->unsigned(false);
            $table->string('name');
            $table->string('email')->unique();
            $table->text('password',255);
            $table->string('profile',255)->default('default.jpg');
            $table->string('type',1)->default('1');
            $table->string('phone',20)->nullable();
            $table->string('address',255)->nullable();
            $table->date('dob')->nullable();
            $table->integer('create_user_id')->default('1');
            $table->foreign('create_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('updated_user_id')->default('1');
            $table->foreign('updated_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('deleted_user_id')->nullable();
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();
            $table->datetime('deleted_at')->nullable();
            $table->unique(['email','deleted_at']);
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
