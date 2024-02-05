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
        Schema::create('front_custom_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->nullable();
            $table->unsignedInteger('front_page_navbar_id')->index('front_custom_pages_front_page_navbar_id_foreign');
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->longText('elements')->nullable();
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
        Schema::dropIfExists('front_custom_pages');
    }
};
