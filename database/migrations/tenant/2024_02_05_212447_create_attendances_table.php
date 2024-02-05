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
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['present', 'late', 'absent', 'half_day', 'holiday', 'weekend', 'leave']);
            $table->unsignedInteger('student_id')->index();
            $table->unsignedInteger('session_id')->index();
            $table->dateTime('clock_in_time')->nullable();
            $table->dateTime('clock_out_time')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('clock_in_note')->nullable();
            $table->text('clock_out_note')->nullable();
            $table->text('remark');
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
        Schema::dropIfExists('attendances');
    }
};
