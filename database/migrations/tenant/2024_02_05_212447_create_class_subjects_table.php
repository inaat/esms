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
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->string('code', 60)->nullable();
            $table->integer('theory_mark')->default(0);
            $table->integer('parc_mark')->default(0);
            $table->integer('total')->default(0);
            $table->tinyInteger('passing_percentage');
            $table->enum('subject_input', ['eng', 'ur'])->nullable();
            $table->unsignedInteger('campus_id')->index('class_subjects_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('class_subjects_class_id_foreign');
            $table->string('bg_color', 32)->default('#fdb46c');
            $table->string('image', 512)->nullable();
            $table->integer('medium_id')->nullable();
            $table->string('type', 64);
            $table->unsignedInteger('created_by')->index('class_subjects_created_by_foreign');
            $table->text('description')->nullable();
            $table->string('subject_icon')->nullable()->default('default.png');
            $table->text('subject_book')->nullable();
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
        Schema::dropIfExists('class_subjects');
    }
};
