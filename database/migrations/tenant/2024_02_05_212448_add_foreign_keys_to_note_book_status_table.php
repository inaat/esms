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
        Schema::table('note_book_status', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['class_section_id'])->references(['id'])->on('class_sections')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['employee_id'])->references(['id'])->on('hrm_employees')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['subject_id'])->references(['id'])->on('class_subjects')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note_book_status', function (Blueprint $table) {
            $table->dropForeign('note_book_status_campus_id_foreign');
            $table->dropForeign('note_book_status_class_id_foreign');
            $table->dropForeign('note_book_status_class_section_id_foreign');
            $table->dropForeign('note_book_status_employee_id_foreign');
            $table->dropForeign('note_book_status_student_id_foreign');
            $table->dropForeign('note_book_status_subject_id_foreign');
        });
    }
};
