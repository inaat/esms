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
        Schema::create('front_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('logo_image');
            $table->string('school_name');
            $table->text('address');
            $table->string('reg_no');
            $table->string('email');
            $table->string('phone_no');
            $table->text('linear_gradient');
            $table->text('main_color');
            $table->text('hover_color');
            $table->text('map_url')->nullable();
            $table->text('facebook')->nullable();
            $table->text('youTube')->nullable();
            $table->text('instagram')->nullable();
            $table->text('linkedin')->nullable();
            $table->text('twitter')->nullable();
            $table->text('skype')->nullable();
            $table->longText('facebook_embed')->nullable();
            $table->enum('admission_open', ['yes', 'no'])->default('no');
            $table->string('admission_banner')->nullable();
            $table->string('page_banner')->nullable();
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
        Schema::dropIfExists('front_settings');
    }
};
