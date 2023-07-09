<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontGalleriesContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_gallery_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('thumb_image');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('front_gallery_categories')->onDelete('cascade');
            $table->text('title');
            $table->text('description');
            $table->enum('status', ['publish','not_publish']);
            $table->text('elements');
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
        Schema::dropIfExists('front_gallery_contents');
    }
}
