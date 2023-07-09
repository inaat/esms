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
        Schema::create('syllabus_mangers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->index('syllabus_mangers_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('syllabus_mangers_class_id_foreign');
            $table->unsignedInteger('exam_term_id')->index('syllabus_mangers_exam_term_id_foreign');
            $table->unsignedInteger('subject_id')->index('syllabus_mangers_subject_id_foreign');
            $table->unsignedInteger('chapter_id')->index('syllabus_mangers_chapter_id_foreign');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('syllabus_mangers');
    }
};
