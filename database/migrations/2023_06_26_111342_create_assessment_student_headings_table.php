<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssessmentStudentHeadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assessment_student_headings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assessment_students_id')->unsigned();
            $table->foreign('assessment_students_id')->references('id')->on('assessment_students')->onDelete('cascade');
            $table->integer('heading_id')->unsigned();
            $table->foreign('heading_id')->references('id')->on('assessment_headings')->onDelete('cascade');
            $table->integer('sub_heading_id')->unsigned();
            $table->foreign('sub_heading_id')->references('id')->on('assessment_sub_headings')->onDelete('cascade');
            $table->tinyInteger('isAverage')->default(0);
            $table->tinyInteger('isGood')->default(0);
            $table->tinyInteger('isPoor')->default(0);
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
        Schema::dropIfExists('assessment_student_headings');
    }
}
