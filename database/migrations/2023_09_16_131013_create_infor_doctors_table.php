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
        Schema::create('infor_doctors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_doctor');
            $table->unsignedBigInteger('id_department');
            $table->unsignedBigInteger('id_hospital');
            $table->boolean('is_confirm')->nullable();
            $table->integer('province_code')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('experience')->nullable();
            $table->integer('gender')->nullable();
            $table->integer('search_number')->default(0);
            $table->timestamps();

            $table->foreign('id_doctor')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_hospital')->references('id_hospital')->on('infor_hospitals')->onDelete('cascade');
            $table->foreign('id_department')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infor_doctors');
    }
};
