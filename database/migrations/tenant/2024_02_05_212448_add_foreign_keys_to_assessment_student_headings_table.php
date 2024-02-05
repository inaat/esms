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
        Schema::table('assessment_student_headings', function (Blueprint $table) {
            $table->foreign(['assessment_students_id'])->references(['id'])->on('assessment_students')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['heading_id'])->references(['id'])->on('assessment_headings')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['sub_heading_id'])->references(['id'])->on('assessment_sub_headings')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_student_headings', function (Blueprint $table) {
            $table->dropForeign('assessment_student_headings_assessment_students_id_foreign');
            $table->dropForeign('assessment_student_headings_heading_id_foreign');
            $table->dropForeign('assessment_student_headings_sub_heading_id_foreign');
        });
    }
};
