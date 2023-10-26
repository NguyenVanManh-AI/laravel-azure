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
        Schema::create('time_works', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hospital')->unique();
            $table->json('times');
            $table->boolean('enable')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('time_works');
    }
};
