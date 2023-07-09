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
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('transport_fee', 22, 4)->default(0);
            $table->unsignedInteger('country_id')->index('regions_country_id_foreign');
            $table->unsignedInteger('province_id')->index('regions_province_id_foreign');
            $table->unsignedInteger('district_id')->index('regions_district_id_foreign');
            $table->unsignedInteger('city_id')->index('regions_city_id_foreign');
            $table->unsignedInteger('system_settings_id')->index('regions_system_settings_id_foreign');
            $table->unsignedInteger('created_by')->index('regions_created_by_foreign');
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
        Schema::dropIfExists('regions');
    }
};
