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
        Schema::create('infor_hospitals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hospital');
            $table->integer('province_code')->nullable();
            $table->json('infrastructure')->nullable();
            $table->text('description')->nullable();
            $table->json('location')->nullable();
            $table->integer('search_number')->default(0);
            $table->timestamps();

            $table->foreign('id_hospital')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infor_hospitals');
    }
};
