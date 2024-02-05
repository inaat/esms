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
        Schema::create('hrm_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->index('hrm_transactions_campus_id_foreign');
            $table->unsignedInteger('session_id')->index('hrm_transactions_session_id_foreign');
            $table->string('payroll_group_name')->nullable();
            $table->enum('type', ['opening_balance', 'pay_roll', 'expense']);
            $table->enum('status', ['pending', 'final']);
            $table->enum('payment_status', ['paid', 'due', 'partial']);
            $table->unsignedInteger('employee_id')->index('hrm_transactions_employee_id_foreign');
            $table->string('ref_no')->nullable();
            $table->dateTime('transaction_date');
            $table->enum('month', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']);
            $table->decimal('basic_salary', 22, 4)->default(0);
            $table->decimal('default_allowance', 22, 4)->default(0);
            $table->decimal('default_deduction', 22, 4)->default(0);
            $table->decimal('allowances_amount', 22, 4)->default(0);
            $table->decimal('deductions_amount', 22, 4)->default(0);
            $table->decimal('final_total', 22, 4)->default(0);
            $table->unsignedInteger('created_by')->index('hrm_transactions_created_by_foreign');
            $table->text('allowances')->nullable();
            $table->text('deductions')->nullable();
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
        Schema::dropIfExists('hrm_transactions');
    }
};
