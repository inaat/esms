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
        Schema::table('exam_subject_results', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['class_section_id'])->references(['id'])->on('class_sections')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['exam_allocation_id'])->references(['id'])->on('exam_allocations')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['exam_create_id'])->references(['id'])->on('exam_creates')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['grade_id'])->references(['id'])->on('exam_grades')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['subject_id'])->references(['id'])->on('class_subjects')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['teacher_id'])->references(['id'])->on('hrm_employees')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_subject_results', function (Blueprint $table) {
            $table->dropForeign('exam_subject_results_campus_id_foreign');
            $table->dropForeign('exam_subject_results_class_id_foreign');
            $table->dropForeign('exam_subject_results_class_section_id_foreign');
            $table->dropForeign('exam_subject_results_exam_allocation_id_foreign');
            $table->dropForeign('exam_subject_results_exam_create_id_foreign');
            $table->dropForeign('exam_subject_results_grade_id_foreign');
            $table->dropForeign('exam_subject_results_session_id_foreign');
            $table->dropForeign('exam_subject_results_student_id_foreign');
            $table->dropForeign('exam_subject_results_subject_id_foreign');
            $table->dropForeign('exam_subject_results_teacher_id_foreign');
        });
    }
};
