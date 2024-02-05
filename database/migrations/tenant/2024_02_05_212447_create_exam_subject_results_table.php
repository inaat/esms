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
        Schema::create('exam_subject_results', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_allocation_id')->index('exam_subject_results_exam_allocation_id_foreign');
            $table->unsignedInteger('exam_create_id')->index('exam_subject_results_exam_create_id_foreign');
            $table->unsignedInteger('session_id')->index('exam_subject_results_session_id_foreign');
            $table->unsignedInteger('student_id')->index('exam_subject_results_student_id_foreign');
            $table->unsignedInteger('campus_id')->index('exam_subject_results_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('exam_subject_results_class_id_foreign');
            $table->unsignedInteger('class_section_id')->index('exam_subject_results_class_section_id_foreign');
            $table->unsignedInteger('subject_id')->index('exam_subject_results_subject_id_foreign');
            $table->unsignedInteger('teacher_id')->nullable()->index('exam_subject_results_teacher_id_foreign');
            $table->integer('theory_mark')->default(0);
            $table->decimal('obtain_theory_mark', 22, 1)->default(0);
            $table->integer('parc_mark')->default(0);
            $table->decimal('obtain_parc_mark', 22, 1)->default(0);
            $table->integer('total_mark')->default(0);
            $table->decimal('total_obtain_mark', 22)->default(0);
            $table->tinyInteger('is_attend')->nullable();
            $table->decimal('pass_percentage', 22, 4)->default(0);
            $table->decimal('obtain_percentage', 22, 4)->default(0);
            $table->unsignedInteger('grade_id')->nullable()->index('exam_subject_results_grade_id_foreign');
            $table->unsignedInteger('position_in_subject')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('exam_subject_results');
    }
};
