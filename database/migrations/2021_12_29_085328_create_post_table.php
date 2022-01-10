<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('description', 2000);
            $table->string('photo', 255);
            $table->integer('id_teacher');
            $table->foreign('id_teacher')->references('id')->on('teachers');
            $table->string('date', 255);
            $table->integer('permisson');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post');
    }
}
