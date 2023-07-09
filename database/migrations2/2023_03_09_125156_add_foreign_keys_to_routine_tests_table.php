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
        Schema::table('routine_tests', function (Blueprint $table) {
            $table->foreign(['teacher_id'])->references(['id'])->on('hrm_employees')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onDelete('CASCADE');
            $table->foreign(['class_section_id'])->references(['id'])->on('class_sections')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onDelete('CASCADE');
            $table->foreign(['subject_id'])->references(['id'])->on('class_subjects')->onDelete('CASCADE');
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onDelete('CASCADE');
            $table->foreign(['grade_id'])->references(['id'])->on('exam_grades')->onDelete('CASCADE');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routine_tests', function (Blueprint $table) {
            $table->dropForeign('routine_tests_teacher_id_foreign');
            $table->dropForeign('routine_tests_campus_id_foreign');
            $table->dropForeign('routine_tests_class_section_id_foreign');
            $table->dropForeign('routine_tests_session_id_foreign');
            $table->dropForeign('routine_tests_subject_id_foreign');
            $table->dropForeign('routine_tests_class_id_foreign');
            $table->dropForeign('routine_tests_grade_id_foreign');
            $table->dropForeign('routine_tests_student_id_foreign');
        });
    }
};
