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
        Schema::table('global_settings', function (Blueprint $table) {
            $table->foreign(['currency_id'])->references(['id'])->on('currencies');
            $table->foreign(['session_id'])->references(['id'])->on('sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropForeign('global_settings_currency_id_foreign');
            $table->dropForeign('global_settings_session_id_foreign');
        });
    }
};
