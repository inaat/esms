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
        Schema::table('expense_transaction_payments', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onDelete('CASCADE');
            $table->foreign(['expense_transaction_id'])->references(['id'])->on('expense_transactions')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expense_transaction_payments', function (Blueprint $table) {
            $table->dropForeign('expense_transaction_payments_campus_id_foreign');
            $table->dropForeign('expense_transaction_payments_session_id_foreign');
            $table->dropForeign('expense_transaction_payments_expense_transaction_id_foreign');
        });
    }
};
