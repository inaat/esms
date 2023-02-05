<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteBookStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('note_book_status', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->date('check_date');
        //     $table->integer('campus_id')->unsigned();
        //     $table->foreign('campus_id')->references('id')->on('campuses');
        //     $table->integer('class_id')->unsigned();
        //     $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        //     $table->integer('class_section_id')->unsigned();
        //     $table->foreign('class_section_id')->references('id')->on('class_sections')->onDelete('cascade');
        //     $table->integer('subject_id')->unsigned();
        //     $table->foreign('subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
        //     $table->integer('student_id')->unsigned();
        //     $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        //     $table->enum('status', ['complete','incomplete','Missing','not_found']);
        //     $table->integer('employee_id')->unsigned();
        //     $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');
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
        // Schema::dropIfExists('note_book_status');
    }
}
