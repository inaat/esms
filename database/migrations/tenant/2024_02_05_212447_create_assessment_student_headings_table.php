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
        Schema::create('assessment_student_headings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assessment_students_id')->index('assessment_student_headings_assessment_students_id_foreign');
            $table->unsignedInteger('heading_id')->index('assessment_student_headings_heading_id_foreign');
            $table->unsignedInteger('sub_heading_id')->index('assessment_student_headings_sub_heading_id_foreign');
            $table->tinyInteger('isAverage')->default(0);
            $table->tinyInteger('isGood')->default(0);
            $table->tinyInteger('isPoor')->default(0);
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
        Schema::dropIfExists('assessment_student_headings');
    }
};
