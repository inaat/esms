<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSubjectResultsTable extends Migration
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
            $table->integer('exam_allocation_id')->unsigned();
            $table->foreign('exam_allocation_id')->references('id')->on('exam_allocations')->onDelete('cascade');
            //new
            $table->integer('exam_create_id')->unsigned();
            $table->foreign('exam_create_id')->references('id')->on('exam_creates')->onDelete('cascade');
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
            ///end_new
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
            $table->integer('teacher_id')->unsigned()->nullable();
            $table->foreign('teacher_id')->references('id')->on('hrm_employees')->onDelete('cascade');
            $table->integer('theory_mark')->default(0);
            $table->integer('obtain_theory_mark')->default(0);
            $table->integer('parc_mark')->default(0);
            $table->integer('obtain_parc_mark')->default(0);
            $table->integer('total_mark')->default(0);
            $table->integer('total_obtain_mark')->default(0);
            $table->tinyInteger('is_attend')->nullable();
            $table->decimal('pass_percentage', 22, 4)->default(0);
            $table->decimal('obtain_percentage', 22, 4)->default(0);
            $table->integer('grade_id')->unsigned()->nullable();
            $table->foreign('grade_id')->references('id')->on('exam_grades')->onDelete('cascade');
            $table->integer('position_in_subject')->unsigned()->nullable();
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
}
