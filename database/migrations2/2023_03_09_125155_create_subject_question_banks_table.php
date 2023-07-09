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
        Schema::create('subject_question_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subject_id')->index('subject_question_banks_subject_id_foreign');
            $table->unsignedInteger('chapter_id')->index('subject_question_banks_chapter_id_foreign');
            $table->unsignedInteger('created_by')->nullable()->index('subject_question_banks_created_by_foreign');
            $table->text('question');
            $table->text('hint')->nullable();
            $table->enum('type', ['mcq', 'fill_in_the_blanks', 'true_and_false', 'column_matching', 'short_question', 'words_and_use', 'paraphrase', 'stanza', 'passage', 'long_question', 'translation_to_urdu', 'translation_to_english', 'contextual', 'singular_and_plural', 'masculine_and_feminine', 'grammar']);
            $table->text('option_a')->nullable();
            $table->text('option_b')->nullable();
            $table->text('option_c')->nullable();
            $table->text('option_d')->nullable();
            $table->text('column_a')->nullable();
            $table->text('column_b')->nullable();
            $table->text('answer')->nullable();
            $table->decimal('marks', 5)->default(0);
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
        Schema::dropIfExists('subject_question_banks');
    }
};
