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
        Schema::create('hospital_departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_department');
            $table->unsignedBigInteger('id_hospital');
            $table->integer('time_advise');
            $table->double('price', 15, 2);
            $table->timestamps();

            $table->foreign('id_department')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('id_hospital')->references('id_hospital')->on('infor_hospitals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospital_departments');
    }
};
