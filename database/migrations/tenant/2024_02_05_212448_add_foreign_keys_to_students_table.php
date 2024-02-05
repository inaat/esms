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
        Schema::table('students', function (Blueprint $table) {
            $table->foreign(['adm_class_id'], 'students_admission_class_id_foreign')->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['adm_class_section_id'])->references(['id'])->on('class_sections')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['adm_session_id'])->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['category_id'])->references(['id'])->on('categories')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['city_id'])->references(['id'])->on('cities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['country_id'])->references(['id'])->on('currencies')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['current_class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['current_class_section_id'])->references(['id'])->on('class_sections')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['cur_session_id'])->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['discount_id'])->references(['id'])->on('discounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['domicile_id'])->references(['id'])->on('districts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['province_id'])->references(['id'])->on('provinces')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['region_id'])->references(['id'])->on('regions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['system_settings_id'])->references(['id'])->on('system_settings')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['vehicle_id'])->references(['id'])->on('vehicles')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign('students_admission_class_id_foreign');
            $table->dropForeign('students_adm_class_section_id_foreign');
            $table->dropForeign('students_adm_session_id_foreign');
            $table->dropForeign('students_campus_id_foreign');
            $table->dropForeign('students_category_id_foreign');
            $table->dropForeign('students_city_id_foreign');
            $table->dropForeign('students_country_id_foreign');
            $table->dropForeign('students_created_by_foreign');
            $table->dropForeign('students_current_class_id_foreign');
            $table->dropForeign('students_current_class_section_id_foreign');
            $table->dropForeign('students_cur_session_id_foreign');
            $table->dropForeign('students_discount_id_foreign');
            $table->dropForeign('students_district_id_foreign');
            $table->dropForeign('students_domicile_id_foreign');
            $table->dropForeign('students_province_id_foreign');
            $table->dropForeign('students_region_id_foreign');
            $table->dropForeign('students_system_settings_id_foreign');
            $table->dropForeign('students_user_id_foreign');
            $table->dropForeign('students_vehicle_id_foreign');
        });
    }
};
