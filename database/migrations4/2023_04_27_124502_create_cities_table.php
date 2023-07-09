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
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('country_id')->index('cities_country_id_foreign');
            $table->unsignedInteger('province_id')->index('cities_province_id_foreign');
            $table->unsignedInteger('district_id')->index('cities_district_id_foreign');
            $table->unsignedInteger('system_settings_id')->index('cities_system_settings_id_foreign');
            $table->unsignedInteger('created_by')->index('cities_created_by_foreign');
            $table->softDeletes();
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
        Schema::dropIfExists('cities');
    }
};
