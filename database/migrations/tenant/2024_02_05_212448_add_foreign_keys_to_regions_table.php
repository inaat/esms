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
        Schema::table('regions', function (Blueprint $table) {
            $table->foreign(['city_id'])->references(['id'])->on('cities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['country_id'])->references(['id'])->on('currencies')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
        Schema::table('regions', function (Blueprint $table) {
            $table->dropForeign('regions_city_id_foreign');
            $table->dropForeign('regions_country_id_foreign');
            $table->dropForeign('regions_created_by_foreign');
            $table->dropForeign('regions_district_id_foreign');
            $table->dropForeign('regions_province_id_foreign');
            $table->dropForeign('regions_system_settings_id_foreign');
        });
    }
};
