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
        Schema::create('note_book_status', function (Blueprint $table) {
            $table->increments('id');
            $table->date('check_date');
            $table->unsignedInteger('campus_id')->index('note_book_status_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('note_book_status_class_id_foreign');
            $table->unsignedInteger('class_section_id')->index('note_book_status_class_section_id_foreign');
            $table->unsignedInteger('subject_id')->index('note_book_status_subject_id_foreign');
            $table->unsignedInteger('student_id')->index('note_book_status_student_id_foreign');
            $table->enum('status', ['complete', 'incomplete', 'Missing', 'not_found']);
            $table->unsignedInteger('employee_id')->index('note_book_status_employee_id_foreign');
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
        Schema::dropIfExists('note_book_status');
    }
};
