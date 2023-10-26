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
        Schema::create('vacation_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_doctor')->nullable();
            $table->timestamp('date');
            $table->integer('shift_off');
            $table->boolean('is_accept')->default(false);
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
        Schema::dropIfExists('vacation_schedules');
    }
};
