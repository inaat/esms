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
        Schema::create('fee_transaction_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fee_transaction_id')->index('fee_transaction_lines_fee_transaction_id_foreign');
            $table->unsignedInteger('fee_head_id')->index('fee_transaction_lines_fee_head_id_foreign');
            $table->decimal('amount', 22, 4)->default(0);
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
        Schema::dropIfExists('fee_transaction_lines');
    }
};
