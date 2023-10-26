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
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_doctor');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_service')->nullable();
            $table->double('price', 15, 2);
            $table->json('time');
            $table->text('content');
            $table->timestamps();
            $table->foreign('id_doctor')->references('id_doctor')->on('infor_doctors')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('infor_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_schedules');
    }
};
