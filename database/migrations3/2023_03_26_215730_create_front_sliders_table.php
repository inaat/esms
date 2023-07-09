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
        Schema::create('front_sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', ['publish', 'not_publish']);
            $table->text('title');
            $table->text('description');
            $table->string('slider_image');
            $table->string('btn_name')->nullable();
            $table->string('btn_url')->nullable();
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
        Schema::dropIfExists('front_sliders');
    }
};
