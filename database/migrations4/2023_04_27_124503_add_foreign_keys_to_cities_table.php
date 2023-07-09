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
        Schema::table('cities', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['province_id'])->references(['id'])->on('provinces');
            $table->foreign(['country_id'])->references(['id'])->on('currencies');
            $table->foreign(['district_id'])->references(['id'])->on('districts');
            $table->foreign(['system_settings_id'])->references(['id'])->on('system_settings')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign('cities_created_by_foreign');
            $table->dropForeign('cities_province_id_foreign');
            $table->dropForeign('cities_country_id_foreign');
            $table->dropForeign('cities_district_id_foreign');
            $table->dropForeign('cities_system_settings_id_foreign');
        });
    }
};
