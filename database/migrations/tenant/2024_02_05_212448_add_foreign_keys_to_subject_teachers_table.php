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
        Schema::table('subject_teachers', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['class_section_id'])->references(['id'])->on('class_sections')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['subject_id'])->references(['id'])->on('class_subjects')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['teacher_id'])->references(['id'])->on('hrm_employees')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_teachers', function (Blueprint $table) {
            $table->dropForeign('subject_teachers_campus_id_foreign');
            $table->dropForeign('subject_teachers_class_id_foreign');
            $table->dropForeign('subject_teachers_class_section_id_foreign');
            $table->dropForeign('subject_teachers_subject_id_foreign');
            $table->dropForeign('subject_teachers_teacher_id_foreign');
        });
    }
};
