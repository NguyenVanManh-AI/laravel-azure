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
        Schema::create('health_insurance_hospitals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hospital');
            $table->unsignedBigInteger('id_health_insurance');
            $table->timestamps();

            $table->foreign('id_hospital')->references('id_hospital')->on('infor_hospitals')->onDelete('cascade');
            $table->foreign('id_health_insurance')->references('id')->on('health_insurances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('health_insurance_hospitals');
    }
};
