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
        Schema::create('routine_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('session_id')->index('routine_tests_session_id_foreign');
            $table->unsignedInteger('student_id')->index('routine_tests_student_id_foreign');
            $table->unsignedInteger('campus_id')->index('routine_tests_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('routine_tests_class_id_foreign');
            $table->unsignedInteger('class_section_id')->index('routine_tests_class_section_id_foreign');
            $table->unsignedInteger('subject_id')->index('routine_tests_subject_id_foreign');
            $table->unsignedInteger('teacher_id')->nullable()->index('routine_tests_teacher_id_foreign');
            $table->enum('type', ['morning', 'evening']);
            $table->integer('obtain_mark')->default(0);
            $table->integer('total_mark')->default(0);
            $table->tinyInteger('is_attend')->nullable();
            $table->decimal('obtain_percentage', 22, 4)->default(0);
            $table->unsignedInteger('grade_id')->nullable()->index('routine_tests_grade_id_foreign');
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
};
