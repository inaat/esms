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
        Schema::create('class_subject_lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('chapter_id');
            $table->unsignedInteger('campus_id')->index('class_subject_lessons_campus_id_foreign');
            $table->unsignedInteger('subject_id')->index('class_subject_lessons_subject_id_foreign');
            $table->text('video_link')->nullable();
            $table->unsignedInteger('created_by')->index('class_subject_lessons_created_by_foreign');
            $table->text('description')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('class_subject_lessons');
    }
};
