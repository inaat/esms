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
        Schema::create('expense_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->index('expense_transactions_campus_id_foreign');
            $table->unsignedInteger('session_id')->index('expense_transactions_session_id_foreign');
            $table->enum('type', ['expense']);
            $table->enum('status', ['pending', 'final']);
            $table->unsignedInteger('expense_category_id')->nullable()->index('expense_transactions_expense_category_id_foreign');
            $table->enum('payment_status', ['paid', 'due', 'partial']);
            $table->unsignedInteger('expense_for')->index('expense_transactions_expense_for_foreign');
            $table->unsignedInteger('vehicle_id')->nullable()->index('expense_transactions_vehicle_id_foreign');
            $table->string('ref_no')->nullable();
            $table->dateTime('transaction_date');
            $table->decimal('final_total', 22, 4)->default(0);
            $table->text('additional_notes')->nullable();
            $table->unsignedInteger('created_by')->index('expense_transactions_created_by_foreign');
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
        Schema::dropIfExists('expense_transactions');
    }
};
