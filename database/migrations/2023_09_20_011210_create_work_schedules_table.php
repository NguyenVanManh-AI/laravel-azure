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
            $table->unsignedBigInteger('id_doctor')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_service')->nullable();
            $table->double('price', 15, 2);
            $table->json('time');
            $table->longText('content');

            $table->boolean('is_confirm')->nullable()->default(false);
            $table->string('name_patient')->nullable();
            $table->date('date_of_birth_patient')->nullable();
            $table->integer('gender_patient')->nullable();
            $table->string('email_patient')->nullable();
            $table->string('phone_patient')->nullable();
            $table->string('address_patient')->nullable();
            $table->longText('health_condition')->nullable();

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
