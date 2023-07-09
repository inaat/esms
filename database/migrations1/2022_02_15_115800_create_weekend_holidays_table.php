<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeekendHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekend_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('from');
            $table->date('to');
            $table->boolean('sms')->default(0);
            $table->text('sms_body');
            $table->text('class_section');
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
        Schema::dropIfExists('weekend_holidays');
    }
}
