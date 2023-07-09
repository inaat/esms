<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamDateSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_date_sheets', function (Blueprint $table) {
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
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->string('day')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('type',['written','oral','written_oral']);
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
        Schema::dropIfExists('exam_date_sheets');
    }
}
