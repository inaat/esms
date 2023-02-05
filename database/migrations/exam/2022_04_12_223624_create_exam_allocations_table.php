<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_allocations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_create_id')->unsigned();
            $table->foreign('exam_create_id')->references('id')->on('exam_creates')->onDelete('cascade');
            $table->integer('session_id')->unsigned();
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->integer('campus_id')->unsigned();
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->integer('class_section_id')->unsigned();
            $table->foreign('class_section_id')->references('id')->on('class_sections')->onDelete('cascade');
            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->enum('roll_type', ['default_roll_no', 'custom_roll_no']);
            $table->string('exam_roll_no')->nullable();
            $table->decimal('total_mark', 22, 4)->default(0);
            $table->decimal('obtain_mark', 22, 4)->default(0);
            $table->decimal('final_percentage', 22, 4)->default(0);
            $table->integer('grade_id')->unsigned()->nullable();
            $table->foreign('grade_id')->references('id')->on('exam_grades')->onDelete('cascade');
            $table->string('remark')->nullable();
            $table->integer('class_position')->unsigned()->nullable();
            $table->integer('class_section_position')->unsigned()->nullable();
            $table->integer('merit_rank_in_school')->unsigned()->nullable();
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
        Schema::dropIfExists('exam_allocations');
    }
}
