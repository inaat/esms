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
        Schema::table('assessment_students', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['class_section_id'])->references(['id'])->on('class_sections')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['employee_id'])->references(['id'])->on('hrm_employees')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_students', function (Blueprint $table) {
            $table->dropForeign('assessment_students_campus_id_foreign');
            $table->dropForeign('assessment_students_class_id_foreign');
            $table->dropForeign('assessment_students_class_section_id_foreign');
            $table->dropForeign('assessment_students_employee_id_foreign');
            $table->dropForeign('assessment_students_student_id_foreign');
        });
    }
};
