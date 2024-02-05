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
        Schema::create('class_subject_progress', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->index('class_subject_progress_campus_id_foreign');
            $table->unsignedInteger('class_id')->nullable();
            $table->unsignedInteger('class_section_id');
            $table->unsignedInteger('subject_id')->index('class_subject_progress_subject_id_foreign');
            $table->unsignedInteger('lesson_id')->index('class_subject_progress_lesson_id_foreign');
            $table->unsignedInteger('created_by')->nullable()->index('class_subject_progress_created_by_foreign');
            $table->unsignedInteger('teacher_by')->nullable()->index('class_subject_progress_teacher_by_foreign');
            $table->unsignedInteger('session_id')->nullable()->index('class_subject_progress_session_id_foreign');
            $table->integer('chapter_id');
            $table->enum('status', ['completed', 'pending', 'reading'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('reading_date')->nullable();
            $table->date('complete_date')->nullable();
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
        Schema::dropIfExists('class_subject_progress');
    }
};
