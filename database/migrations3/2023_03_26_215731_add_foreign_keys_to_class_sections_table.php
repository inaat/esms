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
        Schema::table('class_sections', function (Blueprint $table) {
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onDelete('CASCADE');
            $table->foreign(['system_settings_id'])->references(['id'])->on('system_settings')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onDelete('CASCADE');
            $table->foreign(['created_by'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_sections', function (Blueprint $table) {
            $table->dropForeign('class_sections_class_id_foreign');
            $table->dropForeign('class_sections_system_settings_id_foreign');
            $table->dropForeign('class_sections_campus_id_foreign');
            $table->dropForeign('class_sections_created_by_foreign');
        });
    }
};
