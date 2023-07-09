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
        Schema::table('expense_transactions', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses');
            $table->foreign(['expense_category_id'])->references(['id'])->on('expense_categories')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onDelete('CASCADE');
            $table->foreign(['created_by'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['expense_for'])->references(['id'])->on('hrm_employees')->onDelete('CASCADE');
            $table->foreign(['vehicle_id'])->references(['id'])->on('vehicles')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_transactions', function (Blueprint $table) {
            $table->dropForeign('expense_transactions_campus_id_foreign');
            $table->dropForeign('expense_transactions_expense_category_id_foreign');
            $table->dropForeign('expense_transactions_session_id_foreign');
            $table->dropForeign('expense_transactions_created_by_foreign');
            $table->dropForeign('expense_transactions_expense_for_foreign');
            $table->dropForeign('expense_transactions_vehicle_id_foreign');
        });
    }
};
