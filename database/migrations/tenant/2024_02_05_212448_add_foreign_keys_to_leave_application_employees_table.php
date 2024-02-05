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
        Schema::table('leave_application_employees', function (Blueprint $table) {
            $table->foreign(['approve_by'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['employee_id'])->references(['id'])->on('hrm_employees')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_application_employees', function (Blueprint $table) {
            $table->dropForeign('leave_application_employees_approve_by_foreign');
            $table->dropForeign('leave_application_employees_campus_id_foreign');
            $table->dropForeign('leave_application_employees_employee_id_foreign');
            $table->dropForeign('leave_application_employees_session_id_foreign');
        });
    }
};
