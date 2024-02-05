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
        Schema::table('class_subject_lessons', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['subject_id'])->references(['id'])->on('class_subjects')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_subject_lessons', function (Blueprint $table) {
            $table->dropForeign('class_subject_lessons_campus_id_foreign');
            $table->dropForeign('class_subject_lessons_created_by_foreign');
            $table->dropForeign('class_subject_lessons_subject_id_foreign');
        });
    }
};
