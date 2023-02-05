<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveApplicationEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('leave_application_employees', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->integer('session_id')->unsigned();
        //     $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
        //     $table->integer('campus_id')->unsigned();
        //     $table->foreign('campus_id')->references('id')->on('campuses');
        //     $table->integer('employee_id')->unsigned();
        //     $table->foreign('employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');
        //     $table->date('apply_date');
        //     $table->date('from_date');
        //     $table->date('to_date');
        //     $table->enum('status', ['pending','approve','reject']);
        //     $table->integer('approve_by')->unsigned()->nullable();
        //     $table->foreign('approve_by')->references('id')->on('users')->onDelete('cascade');
        //     $table->string('document')->nullable();
        //     $table->text('reason')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('leave_application_employees');
    }
}
