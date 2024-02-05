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
        Schema::create('class_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('section_name');
            $table->unsignedInteger('class_id')->index('class_sections_class_id_foreign');
            $table->unsignedInteger('system_settings_id')->index('class_sections_system_settings_id_foreign');
            $table->unsignedInteger('campus_id')->nullable()->index('class_sections_campus_id_foreign');
            $table->integer('teacher_id')->nullable();
            $table->text('whatsapp_group_name')->nullable();
            $table->unsignedInteger('created_by')->index('class_sections_created_by_foreign');
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
        Schema::dropIfExists('class_sections');
    }
};
