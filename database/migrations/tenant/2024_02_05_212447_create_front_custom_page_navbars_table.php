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
        Schema::create('front_custom_page_navbars', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->string('slug');
            $table->enum('type', ['download_and_blink', 'page', 'page_with_download']);
            $table->enum('status', ['publish', 'not_publish']);
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
        Schema::dropIfExists('front_custom_page_navbars');
    }
};
