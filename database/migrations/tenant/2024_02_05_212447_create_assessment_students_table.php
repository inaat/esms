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
        Schema::create('assessment_students', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->index('assessment_students_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('assessment_students_class_id_foreign');
            $table->unsignedInteger('class_section_id')->index('assessment_students_class_section_id_foreign');
            $table->unsignedInteger('student_id')->index('assessment_students_student_id_foreign');
            $table->unsignedInteger('employee_id')->index('assessment_students_employee_id_foreign');
            $table->date('assessment_date');
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
        Schema::dropIfExists('assessment_students');
    }
};
