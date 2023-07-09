<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawal_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adm_session_id')->unsigned();
            $table->foreign('adm_session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->integer('student_id')->unsigned()->unique();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->integer('campus_id')->unsigned();
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->integer('admission_class_id')->unsigned();
            $table->foreign('admission_class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->integer('leaving_session_id')->unsigned()->nullable();
            $table->foreign('leaving_session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->integer('leaving_class_id')->unsigned()->nullable();
            $table->foreign('leaving_class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->string('withdraw_reason')->nullable();
            $table->date('date_of_leaving')->nullable();
            $table->date('slc_issue_date')->nullable();
            $table->string('slc_no')->nullable();
            $table->string('any_remarks')->nullable();
            $table->string('co_curricular_activities')->nullable();
            $table->text('local_withdrawal_register_detail')->nullable();
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
        Schema::dropIfExists('withdrawal_registers');
    }
}
