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
        Schema::table('fee_heads', function (Blueprint $table) {
            $table->foreign(['class_id'])->references(['id'])->on('classes')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_heads', function (Blueprint $table) {
            $table->dropForeign('fee_heads_class_id_foreign');
            $table->dropForeign('fee_heads_campus_id_foreign');
        });
    }
};
