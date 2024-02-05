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
        Schema::create('leave_application_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('session_id')->index('leave_application_employees_session_id_foreign');
            $table->unsignedInteger('campus_id')->index('leave_application_employees_campus_id_foreign');
            $table->unsignedInteger('employee_id')->index('leave_application_employees_employee_id_foreign');
            $table->date('apply_date');
            $table->date('from_date');
            $table->date('to_date');
            $table->enum('status', ['pending', 'approve', 'reject']);
            $table->unsignedInteger('approve_by')->nullable()->index('leave_application_employees_approve_by_foreign');
            $table->string('document')->nullable();
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('leave_application_employees');
    }
};
