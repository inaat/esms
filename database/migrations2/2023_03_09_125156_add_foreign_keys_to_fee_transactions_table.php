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
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses');
            $table->foreign(['created_by'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onDelete('CASCADE');
            $table->foreign(['system_settings_id'])->references(['id'])->on('system_settings')->onDelete('CASCADE');
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onDelete('CASCADE');
            $table->foreign(['class_section_id'], 'fee_transactions_section_id_foreign')->references(['id'])->on('class_sections')->onDelete('CASCADE');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->dropForeign('fee_transactions_campus_id_foreign');
            $table->dropForeign('fee_transactions_created_by_foreign');
            $table->dropForeign('fee_transactions_session_id_foreign');
            $table->dropForeign('fee_transactions_system_settings_id_foreign');
            $table->dropForeign('fee_transactions_class_id_foreign');
            $table->dropForeign('fee_transactions_section_id_foreign');
            $table->dropForeign('fee_transactions_student_id_foreign');
        });
    }
};
