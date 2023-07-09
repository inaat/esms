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
        Schema::create('exam_date_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_create_id')->index('exam_date_sheets_exam_create_id_foreign');
            $table->unsignedInteger('session_id')->index('exam_date_sheets_session_id_foreign');
            $table->unsignedInteger('campus_id')->index('exam_date_sheets_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('exam_date_sheets_class_id_foreign');
            $table->unsignedInteger('class_section_id')->nullable()->index('exam_date_sheets_class_section_id_foreign');
            $table->unsignedInteger('subject_id')->index('exam_date_sheets_subject_id_foreign');
            $table->date('date')->nullable();
            $table->string('day')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('type', ['written', 'oral', 'written_oral']);
            $table->text('topic')->nullable();
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
        Schema::dropIfExists('exam_date_sheets');
    }
};
