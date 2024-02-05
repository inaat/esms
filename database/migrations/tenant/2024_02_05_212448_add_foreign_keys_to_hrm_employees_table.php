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
        Schema::table('hrm_employees', function (Blueprint $table) {
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['city_id'])->references(['id'])->on('cities')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['country_id'])->references(['id'])->on('currencies')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['department_id'])->references(['id'])->on('hrm_departments')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['designation_id'])->references(['id'])->on('hrm_designations')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['district_id'])->references(['id'])->on('districts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['education_id'])->references(['id'])->on('hrm_education')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['province_id'])->references(['id'])->on('provinces')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['region_id'])->references(['id'])->on('regions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrm_employees', function (Blueprint $table) {
            $table->dropForeign('hrm_employees_campus_id_foreign');
            $table->dropForeign('hrm_employees_city_id_foreign');
            $table->dropForeign('hrm_employees_country_id_foreign');
            $table->dropForeign('hrm_employees_department_id_foreign');
            $table->dropForeign('hrm_employees_designation_id_foreign');
            $table->dropForeign('hrm_employees_district_id_foreign');
            $table->dropForeign('hrm_employees_education_id_foreign');
            $table->dropForeign('hrm_employees_province_id_foreign');
            $table->dropForeign('hrm_employees_region_id_foreign');
            $table->dropForeign('hrm_employees_user_id_foreign');
        });
    }
};
