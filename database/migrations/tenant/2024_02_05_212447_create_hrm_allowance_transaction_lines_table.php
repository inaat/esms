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
        Schema::create('hrm_allowance_transaction_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hrm_transaction_id')->index('hrm_allowance_transaction_lines_hrm_transaction_id_foreign');
            $table->boolean('is_enabled')->default(false);
            $table->integer('divider')->default(0);
            $table->unsignedInteger('allowance_id')->index('hrm_allowance_transaction_lines_allowance_id_foreign');
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
        Schema::dropIfExists('hrm_allowance_transaction_lines');
    }
};
