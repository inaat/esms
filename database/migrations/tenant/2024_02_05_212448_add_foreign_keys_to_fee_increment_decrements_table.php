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
        Schema::table('fee_increment_decrements', function (Blueprint $table) {
            $table->foreign(['campus_id'], 'fee_increment_decrement_campus_id_foreign')->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['class_id'], 'fee_increment_decrement_class_id_foreign')->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['class_section_id'], 'fee_increment_decrement_class_section_id_foreign')->references(['id'])->on('class_sections')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['session_id'], 'fee_increment_decrement_session_id_foreign')->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_increment_decrements', function (Blueprint $table) {
            $table->dropForeign('fee_increment_decrement_campus_id_foreign');
            $table->dropForeign('fee_increment_decrement_class_id_foreign');
            $table->dropForeign('fee_increment_decrement_class_section_id_foreign');
            $table->dropForeign('fee_increment_decrement_session_id_foreign');
        });
    }
};
