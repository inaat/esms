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
        Schema::table('hrm_attendances', function (Blueprint $table) {
            $table->foreign(['employee_id'])->references(['id'])->on('hrm_employees')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrm_attendances', function (Blueprint $table) {
            $table->dropForeign('hrm_attendances_employee_id_foreign');
            $table->dropForeign('hrm_attendances_session_id_foreign');
        });
    }
};
