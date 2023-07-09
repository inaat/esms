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
        Schema::table('leave_application_students', function (Blueprint $table) {
            $table->foreign(['approve_by'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses');
            $table->foreign(['class_section_id'])->references(['id'])->on('class_sections')->onDelete('CASCADE');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_application_students', function (Blueprint $table) {
            $table->dropForeign('leave_application_students_approve_by_foreign');
            $table->dropForeign('leave_application_students_class_id_foreign');
            $table->dropForeign('leave_application_students_session_id_foreign');
            $table->dropForeign('leave_application_students_campus_id_foreign');
            $table->dropForeign('leave_application_students_class_section_id_foreign');
            $table->dropForeign('leave_application_students_student_id_foreign');
        });
    }
};
