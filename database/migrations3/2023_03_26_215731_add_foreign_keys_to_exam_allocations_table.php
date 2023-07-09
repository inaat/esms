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
        Schema::table('exam_allocations', function (Blueprint $table) {
            $table->foreign(['student_id'])->references(['id'])->on('students')->onDelete('CASCADE');
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onDelete('CASCADE');
            $table->foreign(['exam_create_id'])->references(['id'])->on('exam_creates')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onDelete('CASCADE');
            $table->foreign(['class_section_id'])->references(['id'])->on('class_sections')->onDelete('CASCADE');
            $table->foreign(['grade_id'])->references(['id'])->on('exam_grades')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_allocations', function (Blueprint $table) {
            $table->dropForeign('exam_allocations_student_id_foreign');
            $table->dropForeign('exam_allocations_class_id_foreign');
            $table->dropForeign('exam_allocations_exam_create_id_foreign');
            $table->dropForeign('exam_allocations_session_id_foreign');
            $table->dropForeign('exam_allocations_campus_id_foreign');
            $table->dropForeign('exam_allocations_class_section_id_foreign');
            $table->dropForeign('exam_allocations_grade_id_foreign');
        });
    }
};
