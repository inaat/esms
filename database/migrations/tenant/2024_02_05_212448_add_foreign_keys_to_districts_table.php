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
        Schema::table('districts', function (Blueprint $table) {
            $table->foreign(['country_id'])->references(['id'])->on('currencies')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['province_id'])->references(['id'])->on('provinces')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['system_settings_id'])->references(['id'])->on('system_settings')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->dropForeign('districts_country_id_foreign');
            $table->dropForeign('districts_created_by_foreign');
            $table->dropForeign('districts_province_id_foreign');
            $table->dropForeign('districts_system_settings_id_foreign');
        });
    }
};
