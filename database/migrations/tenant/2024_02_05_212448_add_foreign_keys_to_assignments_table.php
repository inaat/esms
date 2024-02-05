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
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['class_section_id'])->references(['id'])->on('class_sections')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['subject_id'])->references(['id'])->on('class_subjects')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['teacher_id'])->references(['id'])->on('hrm_employees')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign('assignments_class_id_foreign');
            $table->dropForeign('assignments_class_section_id_foreign');
            $table->dropForeign('assignments_session_id_foreign');
            $table->dropForeign('assignments_subject_id_foreign');
            $table->dropForeign('assignments_teacher_id_foreign');
        });
    }
};
