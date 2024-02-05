<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomPagesTable extends Migration
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
            $table->integer('front_page_navbar_id')->unsigned();
            $table->foreign('front_page_navbar_id')->references('id')->on('front_custom_page_navbars')->onDelete('cascade');
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
    }
}
