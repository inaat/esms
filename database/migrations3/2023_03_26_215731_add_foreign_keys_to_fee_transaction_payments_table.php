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
        Schema::table('fee_transaction_payments', function (Blueprint $table) {
            $table->foreign(['fee_transaction_id'])->references(['id'])->on('fee_transactions')->onDelete('CASCADE');
            $table->foreign(['system_settings_id'])->references(['id'])->on('system_settings')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses');
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
        Schema::table('fee_transaction_payments', function (Blueprint $table) {
            $table->dropForeign('fee_transaction_payments_fee_transaction_id_foreign');
            $table->dropForeign('fee_transaction_payments_system_settings_id_foreign');
            $table->dropForeign('fee_transaction_payments_campus_id_foreign');
            $table->dropForeign('fee_transaction_payments_session_id_foreign');
        });
    }
};
