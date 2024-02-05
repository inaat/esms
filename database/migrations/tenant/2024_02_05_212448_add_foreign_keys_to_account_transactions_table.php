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
        Schema::table('account_transactions', function (Blueprint $table) {
            $table->foreign(['expense_transaction_payment_id'])->references(['id'])->on('expense_transaction_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['hrm_transaction_payment_id'])->references(['id'])->on('hrm_transaction_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['transaction_payment_id'])->references(['id'])->on('fee_transaction_payments')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_transactions', function (Blueprint $table) {
            $table->dropForeign('account_transactions_expense_transaction_payment_id_foreign');
            $table->dropForeign('account_transactions_hrm_transaction_payment_id_foreign');
            $table->dropForeign('account_transactions_transaction_payment_id_foreign');
        });
    }
};
