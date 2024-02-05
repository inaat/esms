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
        Schema::table('syllabus_mangers', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['chapter_id'])->references(['id'])->on('subject_chapters')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['exam_term_id'])->references(['id'])->on('exam_terms')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('syllabus_mangers', function (Blueprint $table) {
            $table->dropForeign('syllabus_mangers_campus_id_foreign');
            $table->dropForeign('syllabus_mangers_chapter_id_foreign');
            $table->dropForeign('syllabus_mangers_class_id_foreign');
            $table->dropForeign('syllabus_mangers_exam_term_id_foreign');
            $table->dropForeign('syllabus_mangers_subject_id_foreign');
        });
    }
};
