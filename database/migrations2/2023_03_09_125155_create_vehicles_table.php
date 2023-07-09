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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->nullable();
            $table->string('name');
            $table->unsignedInteger('employee_id')->nullable()->index('vehicles_employee_id_foreign');
            $table->string('driver_license')->nullable();
            $table->string('year_made')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('vehicle_model')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
};
