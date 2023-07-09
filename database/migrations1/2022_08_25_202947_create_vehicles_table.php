<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
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
            $table->string('name');
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');
            $table->string('driver_license')->nullable();
            $table->string('year_made')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('students', function (Blueprint $table) {
            $table->integer('vehicle_id')->unsigned()->nullable()->after('student_transport_fee');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
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
}
