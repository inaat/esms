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
        Schema::create('front_about_us', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->enum('status', ['publish', 'not_publish']);
            $table->text('title');
            $table->text('home_title')->nullable();
            $table->text('description');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('front_about_us');
    }
};
