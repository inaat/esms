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
        Schema::create('hrm_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->index('hrm_employees_user_id_foreign');
            $table->unsignedInteger('campus_id')->nullable()->index('hrm_employees_campus_id_foreign');
            $table->string('employeeID', 20)->unique();
            $table->string('old_EmpID')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email', 150)->unique();
            $table->string('password', 100)->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('father_name', 100);
            $table->string('mobile_no', 20);
            $table->decimal('basic_salary', 22, 4)->nullable();
            $table->decimal('default_allowance', 22, 4)->default(0);
            $table->decimal('default_deduction', 22, 4)->default(0);
            $table->string('pay_period')->nullable();
            $table->string('pay_cycle')->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedInteger('department_id')->nullable()->index('hrm_employees_department_id_foreign');
            $table->unsignedInteger('designation_id')->nullable()->index('hrm_employees_designation_id_foreign');
            $table->unsignedInteger('education_id')->nullable()->index('hrm_employees_education_id_foreign');
            $table->text('education_ids')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('employee_image')->nullable()->default('default.png');
            $table->unsignedInteger('country_id')->nullable()->index('hrm_employees_country_id_foreign');
            $table->unsignedInteger('province_id')->nullable()->index('hrm_employees_province_id_foreign');
            $table->unsignedInteger('district_id')->nullable()->index('hrm_employees_district_id_foreign');
            $table->unsignedInteger('city_id')->nullable()->index('hrm_employees_city_id_foreign');
            $table->unsignedInteger('region_id')->nullable()->index('hrm_employees_region_id_foreign');
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('nationality')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->integer('annual_leave')->default(0);
            $table->enum('status', ['active', 'inactive', 'resign'])->default('active');
            $table->enum('religion', ['Islam', 'Hinduism', 'Christianity', 'Sikhism', 'Buddhism', 'Secular/Nonreligious/Agnostic/Atheist', 'Other'])->default('Islam');
            $table->string('cnic_no')->nullable();
            $table->enum('blood_group', ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'])->nullable();
            $table->longText('bank_details')->nullable();
            $table->text('resign_remark')->nullable();
            $table->text('remark')->nullable();
            $table->tinyInteger('M_Status')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->rememberToken();
            $table->date('exit_date')->nullable();
            $table->string('reset_code')->nullable();
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
        Schema::dropIfExists('hrm_employees');
    }
};
