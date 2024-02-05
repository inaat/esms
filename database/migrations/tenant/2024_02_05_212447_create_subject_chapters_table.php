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
        Schema::create('subject_chapters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subject_id')->index('subject_chapters_subject_id_foreign');
            $table->string('chapter_name');
            $table->text('description')->nullable();
            $table->string('chapter_icon')->nullable()->default('default.png');
            $table->text('video_link')->nullable();
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
        Schema::dropIfExists('subject_chapters');
    }
};
