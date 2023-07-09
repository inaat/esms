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
        Schema::create('exam_allocations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_create_id')->index('exam_allocations_exam_create_id_foreign');
            $table->unsignedInteger('session_id')->index('exam_allocations_session_id_foreign');
            $table->unsignedInteger('campus_id')->index('exam_allocations_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('exam_allocations_class_id_foreign');
            $table->unsignedInteger('class_section_id')->index('exam_allocations_class_section_id_foreign');
            $table->unsignedInteger('student_id')->index('exam_allocations_student_id_foreign');
            $table->enum('roll_type', ['default_roll_no', 'custom_roll_no']);
            $table->string('exam_roll_no')->nullable();
            $table->decimal('total_mark', 22, 4)->default(0);
            $table->decimal('obtain_mark', 22, 4)->default(0);
            $table->decimal('final_percentage', 22, 4)->default(0);
            $table->unsignedInteger('grade_id')->nullable()->index('exam_allocations_grade_id_foreign');
            $table->string('remark')->nullable();
            $table->unsignedInteger('class_position')->nullable();
            $table->unsignedInteger('class_section_position')->nullable();
            $table->unsignedInteger('merit_rank_in_school')->nullable();
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
        Schema::dropIfExists('exam_allocations');
    }
};
