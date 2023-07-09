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
        Schema::table('subject_question_banks', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['chapter_id'])->references(['id'])->on('subject_chapters')->onDelete('CASCADE');
            $table->foreign(['subject_id'])->references(['id'])->on('class_subjects')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_question_banks', function (Blueprint $table) {
            $table->dropForeign('subject_question_banks_created_by_foreign');
            $table->dropForeign('subject_question_banks_chapter_id_foreign');
            $table->dropForeign('subject_question_banks_subject_id_foreign');
        });
    }
};
