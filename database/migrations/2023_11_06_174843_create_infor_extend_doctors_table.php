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
        Schema::create('infor_extend_doctors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_doctor');
            $table->json('prominent')->nullable();
            $table->longText('information')->nullable();
            $table->json('strengths')->nullable();
            $table->json('work_experience')->nullable();
            $table->json('training_process')->nullable();
            $table->json('language')->nullable();
            $table->json('awards_recognition')->nullable();
            $table->json('research_work')->nullable();
            $table->timestamps();

            $table->foreign('id_doctor')->references('id_doctor')->on('infor_doctors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infor_extend_doctors');
    }
};
