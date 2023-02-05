<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeIncrementDecrementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('fee_increment_decrement', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('session_id')->unsigned();
        //     $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
        //     $table->integer('campus_id')->unsigned();
        //     $table->foreign('campus_id')->references('id')->on('campuses');
        //     $table->integer('class_id')->unsigned();
        //     $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        //     $table->integer('class_section_id')->unsigned()->nullable();
        //     $table->foreign('class_section_id')->references('id')->on('class_sections')->onDelete('cascade');
        //     $table->decimal('tuition_fee', 22, 4)->default(0);
        //     $table->decimal('transport_fee', 22, 4)->default(0);
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('fee_increment_decrement');
    }
}
