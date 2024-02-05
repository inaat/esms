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
        Schema::table('hrm_allowance_transaction_lines', function (Blueprint $table) {
            $table->foreign(['allowance_id'])->references(['id'])->on('hrm_allowances')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['hrm_transaction_id'])->references(['id'])->on('hrm_transactions')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrm_allowance_transaction_lines', function (Blueprint $table) {
            $table->dropForeign('hrm_allowance_transaction_lines_allowance_id_foreign');
            $table->dropForeign('hrm_allowance_transaction_lines_hrm_transaction_id_foreign');
        });
    }
};
