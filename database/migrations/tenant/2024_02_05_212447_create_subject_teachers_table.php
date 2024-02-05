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
        Schema::create('subject_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->index('subject_teachers_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('subject_teachers_class_id_foreign');
            $table->unsignedInteger('class_section_id')->index('subject_teachers_class_section_id_foreign');
            $table->unsignedInteger('subject_id')->index('subject_teachers_subject_id_foreign');
            $table->unsignedInteger('teacher_id')->nullable()->index('subject_teachers_teacher_id_foreign');
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
        Schema::dropIfExists('subject_teachers');
    }
};
