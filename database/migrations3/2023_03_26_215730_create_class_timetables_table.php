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
        Schema::create('class_timetables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->index('class_timetables_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('class_timetables_class_id_foreign');
            $table->unsignedInteger('class_section_id')->index('class_timetables_class_section_id_foreign');
            $table->unsignedInteger('subject_id')->nullable()->index('class_timetables_subject_id_foreign');
            $table->text('multi_subject_ids')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->text('multi_teacher')->nullable();
            $table->unsignedInteger('period_id')->index('class_timetables_period_id_foreign');
            $table->enum('other', ['drill', 'nazira', 'written', 'oral', 'nazira_drill', 'spoken', 'religious_study', 'religious_study_spoken'])->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('class_timetables');
    }
};
