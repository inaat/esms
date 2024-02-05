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
        Schema::table('online_applicants', function (Blueprint $table) {
            $table->foreign(['adm_class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['adm_session_id'])->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['city_id'])->references(['id'])->on('cities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['country_id'])->references(['id'])->on('currencies')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['domicile_id'])->references(['id'])->on('districts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['province_id'])->references(['id'])->on('provinces')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_applicants', function (Blueprint $table) {
            $table->dropForeign('online_applicants_adm_class_id_foreign');
            $table->dropForeign('online_applicants_adm_session_id_foreign');
            $table->dropForeign('online_applicants_campus_id_foreign');
            $table->dropForeign('online_applicants_city_id_foreign');
            $table->dropForeign('online_applicants_country_id_foreign');
            $table->dropForeign('online_applicants_district_id_foreign');
            $table->dropForeign('online_applicants_domicile_id_foreign');
            $table->dropForeign('online_applicants_province_id_foreign');
        });
    }
};
