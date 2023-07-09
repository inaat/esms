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
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->enum('type', ['debit', 'credit'])->index();
            $table->enum('sub_type', ['opening_balance', 'fund_transfer', 'deposit', 'debit'])->nullable()->index();
            $table->decimal('amount', 22, 4);
            $table->string('reff_no')->nullable();
            $table->dateTime('operation_date');
            $table->integer('created_by')->index();
            $table->integer('transaction_id')->nullable()->index();
            $table->unsignedInteger('expense_transaction_payment_id')->nullable()->index('account_transactions_expense_transaction_payment_id_foreign');
            $table->unsignedInteger('hrm_transaction_payment_id')->nullable()->index('account_transactions_hrm_transaction_payment_id_foreign');
            $table->unsignedInteger('transaction_payment_id')->nullable()->index('account_transactions_transaction_payment_id_foreign');
            $table->integer('transfer_transaction_id')->nullable()->index();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_transactions');
    }
};
