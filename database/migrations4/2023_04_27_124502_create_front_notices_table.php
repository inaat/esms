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
        Schema::create('front_notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('slug')->nullable();
            $table->text('description');
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
        Schema::dropIfExists('front_notices');
    }
};
