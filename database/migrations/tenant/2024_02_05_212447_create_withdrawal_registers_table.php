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
        Schema::create('withdrawal_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('local_reg_no')->nullable();
            $table->enum('status', ['C', 'M'])->nullable();
            $table->unsignedInteger('adm_session_id')->index('withdrawal_registers_adm_session_id_foreign');
            $table->unsignedInteger('student_id')->unique();
            $table->unsignedInteger('campus_id')->index('withdrawal_registers_campus_id_foreign');
            $table->unsignedInteger('admission_class_id')->index('withdrawal_registers_admission_class_id_foreign');
            $table->unsignedInteger('leaving_session_id')->nullable()->index('withdrawal_registers_leaving_session_id_foreign');
            $table->unsignedInteger('leaving_class_id')->nullable()->index('withdrawal_registers_leaving_class_id_foreign');
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
};
