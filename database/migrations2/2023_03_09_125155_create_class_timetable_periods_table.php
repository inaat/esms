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
        Schema::create('class_timetable_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->index('class_timetable_periods_campus_id_foreign');
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('total_time', 5)->nullable();
            $table->enum('type', ['prayer_time', 'study_period', 'lunch_break'])->default('study_period');
            $table->softDeletes();
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
        Schema::dropIfExists('class_timetable_periods');
    }
};
