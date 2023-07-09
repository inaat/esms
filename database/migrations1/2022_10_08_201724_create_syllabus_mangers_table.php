<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusMangersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('syllabus_mangers', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('campus_id')->unsigned();
        //     $table->foreign('campus_id')->references('id')->on('campuses');
        //     $table->integer('class_id')->unsigned();
        //     $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        //     $table->integer('exam_term_id')->unsigned();
        //     $table->foreign('exam_term_id')->references('id')->on('exam_terms')->onDelete('cascade');
        //     $table->integer('subject_id')->unsigned();
        //     $table->foreign('subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
        //     $table->integer('chapter_id')->unsigned();
        //     $table->foreign('chapter_id')->references('id')->on('subject_chapters')->onDelete('cascade');
        //     // $table->integer('lesson_id')->unsigned();
        //     // $table->foreign('lesson_id')->references('id')->on('class_subject_lessons')->onDelete('cascade');
        //     $table->text('description')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('syllabus_mangers');
    }
}
