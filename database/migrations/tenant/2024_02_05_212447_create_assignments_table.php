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
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('class_id')->index('assignments_class_id_foreign');
            $table->integer('campus_id')->nullable();
            $table->unsignedInteger('class_section_id')->index('assignments_class_section_id_foreign');
            $table->unsignedInteger('subject_id')->index('assignments_subject_id_foreign');
            $table->unsignedInteger('teacher_id')->nullable()->index('assignments_teacher_id_foreign');
            $table->string('name', 128);
            $table->string('instructions', 1024)->nullable();
            $table->dateTime('due_date');
            $table->integer('points')->nullable();
            $table->boolean('resubmission')->default(false);
            $table->integer('extra_days_for_resubmission')->nullable();
            $table->unsignedInteger('session_id')->index('assignments_session_id_foreign');
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
};
