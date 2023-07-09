<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campus_id')->unsigned()->nullable();
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->integer('class_section_id')->unsigned();
            $table->foreign('class_section_id')->references('id')->on('class_sections');
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
            $table->integer('teacher_id')->unsigned()->nullable();
            $table->foreign('teacher_id')->references('id')->on('hrm_employees')->onDelete('cascade');
            $table->string('name', 128);
            $table->string('instructions', 1024)->nullable();
            $table->dateTime('due_date');
            $table->integer('points')->nullable();
            $table->boolean('resubmission')->default(0);
            $table->integer('extra_days_for_resubmission')->nullable();
            $table->integer('session_id')->unsigned();
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}