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
        Schema::table('hrm_transactions', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses');
            $table->foreign(['employee_id'])->references(['id'])->on('hrm_employees')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrm_transactions', function (Blueprint $table) {
            $table->dropForeign('hrm_transactions_created_by_foreign');
            $table->dropForeign('hrm_transactions_session_id_foreign');
            $table->dropForeign('hrm_transactions_campus_id_foreign');
            $table->dropForeign('hrm_transactions_employee_id_foreign');
        });
    }
};
