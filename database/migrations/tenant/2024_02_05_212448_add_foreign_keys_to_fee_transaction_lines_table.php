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
        Schema::table('fee_transaction_lines', function (Blueprint $table) {
            $table->foreign(['fee_head_id'])->references(['id'])->on('fee_heads')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['fee_transaction_id'])->references(['id'])->on('fee_transactions')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_transaction_lines', function (Blueprint $table) {
            $table->dropForeign('fee_transaction_lines_fee_head_id_foreign');
            $table->dropForeign('fee_transaction_lines_fee_transaction_id_foreign');
        });
    }
};
