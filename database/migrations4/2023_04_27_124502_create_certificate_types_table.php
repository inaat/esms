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
        Schema::create('certificate_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('background_image');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('school_logo')->default(0);
            $table->tinyInteger('qrcode')->default(0);
            $table->tinyInteger('builtin')->default(0);
            $table->tinyInteger('school_name')->default(0);
            $table->text('footer_left_text')->nullable();
            $table->text('footer_middle_text')->nullable();
            $table->text('footer_right_text')->nullable();
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
        Schema::dropIfExists('certificate_types');
    }
};
