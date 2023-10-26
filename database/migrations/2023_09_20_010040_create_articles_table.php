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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_category')->nullable();
            $table->string('title');
            $table->text('content');
            $table->string('thumbnail')->nullable();
            $table->integer('search_number')->default(0);
            $table->boolean('is_accept')->nullable();
            $table->boolean('is_show')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_category')->references('id')->on('categories')->onDelete('cascade');
            // nếu cùng một trường liên kết đến nhiều bảng thì làm như này
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
