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
        Schema::create('hospital_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hospital_department');
            $table->string('name');
            $table->integer('time_advise');
            $table->double('price', 15, 2);
            $table->json('infor');
            $table->integer('search_number_service')->default(0);
            $table->boolean('is_delete');
            $table->string('thumbnail_service')->nullable();
            $table->timestamps();

            $table->foreign('id_hospital_department')->references('id')->on('hospital_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospital_services');
    }
};
