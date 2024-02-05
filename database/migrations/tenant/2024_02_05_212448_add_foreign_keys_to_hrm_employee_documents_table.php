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
        Schema::table('hrm_employee_documents', function (Blueprint $table) {
            $table->foreign(['employee_id'])->references(['id'])->on('hrm_employees')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrm_employee_documents', function (Blueprint $table) {
            $table->dropForeign('hrm_employee_documents_employee_id_foreign');
        });
    }
};
