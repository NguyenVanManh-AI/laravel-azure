<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('is_accept')->nullable();
            $table->string('role');
            $table->string('token_verify_email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
