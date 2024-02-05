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
        Schema::table('hrm_transaction_payments', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['hrm_transaction_id'])->references(['id'])->on('hrm_transactions')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('hrm_transaction_payments', function (Blueprint $table) {
            $table->dropForeign('hrm_transaction_payments_campus_id_foreign');
            $table->dropForeign('hrm_transaction_payments_hrm_transaction_id_foreign');
            $table->dropForeign('hrm_transaction_payments_session_id_foreign');
        });
    }
};
