<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutineTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routine_tests', function (Blueprint $table) {
                $table->increments('id');
                $table->date('date');
                $table->integer('session_id')->unsigned();
                $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
                $table->integer('student_id')->unsigned();
                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
                $table->integer('campus_id')->unsigned();
                $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
                $table->integer('class_id')->unsigned();
                $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
                $table->integer('class_section_id')->unsigned();
                $table->foreign('class_section_id')->references('id')->on('class_sections')->onDelete('cascade');
                $table->integer('subject_id')->unsigned();
                $table->foreign('subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
                $table->integer('teacher_id')->unsigned()->nullable();
                $table->foreign('teacher_id')->references('id')->on('hrm_employees')->onDelete('cascade');
                $table->enum('type', ['morning','evening']);
                $table->integer('obtain_mark')->default(0);
                $table->integer('total_mark')->default(0);
                $table->tinyInteger('is_attend')->nullable();
                $table->decimal('obtain_percentage', 22, 4)->default(0);
                $table->integer('grade_id')->unsigned()->nullable();
                $table->foreign('grade_id')->references('id')->on('exam_grades')->onDelete('cascade');
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
        Schema::dropIfExists('routine_tests');
    }
}
