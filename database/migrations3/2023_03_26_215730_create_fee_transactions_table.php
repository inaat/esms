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
        Schema::create('fee_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('system_settings_id')->index('fee_transactions_system_settings_id_foreign');
            $table->unsignedInteger('campus_id')->index('fee_transactions_campus_id_foreign');
            $table->unsignedInteger('session_id')->index('fee_transactions_session_id_foreign');
            $table->unsignedInteger('class_id')->index('fee_transactions_class_id_foreign');
            $table->unsignedInteger('class_section_id')->index('fee_transactions_section_id_foreign');
            $table->enum('type', ['opening_balance', 'fee', 'admission_fee', 'other_fee', 'transport_fee']);
            $table->enum('status', ['pending', 'final']);
            $table->enum('payment_status', ['paid', 'due', 'partial']);
            $table->unsignedInteger('student_id')->index('fee_transactions_student_id_foreign');
            $table->string('voucher_no')->nullable();
            $table->string('ref_no')->nullable();
            $table->dateTime('transaction_date');
            $table->date('due_date')->nullable();
            $table->enum('month', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12']);
            $table->decimal('before_discount_total', 22, 4)->default(0);
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_amount', 22, 4)->default(0);
            $table->decimal('final_total', 22, 4)->default(0);
            $table->unsignedInteger('created_by')->index('fee_transactions_created_by_foreign');
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
        Schema::dropIfExists('fee_transactions');
    }
};
