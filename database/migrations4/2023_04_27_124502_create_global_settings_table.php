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
        Schema::create('global_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('school_name');
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->text('registration_code')->nullable();
            $table->date('registration_date')->nullable();
            $table->enum('school_type', ['boys', 'girls', 'boys_and_girls'])->default('boys');
            $table->enum('school_level', ['primary', 'middle', 'high', 'inter'])->default('inter');
            $table->unsignedInteger('currency_id')->index('global_settings_currency_id_foreign');
            $table->date('start_date')->nullable();
            $table->string('date_format')->default('m/d/Y');
            $table->enum('time_format', ['12', '24'])->default('24');
            $table->string('time_zone')->default('Asia/Karachi');
            $table->string('start_month', 40);
            $table->integer('biometric')->nullable()->default(0);
            $table->text('biometric_device')->nullable();
            $table->string('is_rtl', 10)->nullable()->default('disabled');
            $table->integer('is_duplicate_fees_invoice')->nullable()->default(0);
            $table->unsignedInteger('session_id')->nullable()->index('session_id');
            $table->string('cron_secret_key', 100);
            $table->enum('currency_symbol_placement', ['before', 'after'])->default('before');
            $table->boolean('attendence_type')->default(false);
            $table->string('image', 100)->nullable();
            $table->string('admin_logo');
            $table->string('admin_small_logo');
            $table->integer('fee_due_days')->nullable()->default(0);
            $table->boolean('adm_auto_insert')->default(true);
            $table->string('adm_prefix', 50)->default('ssadm19/20');
            $table->string('adm_start_from', 11);
            $table->integer('adm_no_digit')->default(6);
            $table->boolean('adm_update_status')->default(false);
            $table->integer('staffid_auto_insert')->default(1);
            $table->string('staffid_prefix', 100)->default('staffss/19/20');
            $table->string('staffid_start_from', 50);
            $table->integer('staffid_no_digit')->default(6);
            $table->integer('staffid_update_status')->default(0);
            $table->string('is_active')->nullable()->default('0');
            $table->integer('online_admission')->nullable()->default(0);
            $table->boolean('is_blood_group')->default(true);
            $table->boolean('is_student_house')->default(true);
            $table->boolean('roll_no')->default(true);
            $table->boolean('category')->default(true);
            $table->boolean('religion')->default(true);
            $table->boolean('cast')->default(true);
            $table->boolean('mobile_no')->default(true);
            $table->boolean('student_email')->default(true);
            $table->boolean('admission_date')->default(true);
            $table->boolean('lastname')->default(true);
            $table->boolean('middlename')->default(true);
            $table->boolean('student_photo')->default(true);
            $table->boolean('student_height')->default(true);
            $table->boolean('student_weight')->default(true);
            $table->boolean('measurement_date')->default(true);
            $table->boolean('father_name')->default(true);
            $table->boolean('father_phone')->default(true);
            $table->boolean('father_occupation')->default(true);
            $table->boolean('father_pic')->default(true);
            $table->boolean('mother_name')->default(true);
            $table->boolean('mother_phone')->default(true);
            $table->boolean('mother_occupation')->default(true);
            $table->boolean('mother_pic')->default(true);
            $table->boolean('guardian_name')->default(true);
            $table->boolean('guardian_relation')->default(true);
            $table->boolean('guardian_phone')->default(true);
            $table->boolean('guardian_email')->default(true);
            $table->boolean('guardian_pic')->default(true);
            $table->boolean('guardian_occupation')->default(true);
            $table->boolean('guardian_address')->default(true);
            $table->boolean('current_address')->default(true);
            $table->boolean('permanent_address')->default(true);
            $table->boolean('route_list')->default(true);
            $table->boolean('hostel_id')->default(true);
            $table->boolean('bank_account_no')->default(true);
            $table->boolean('ifsc_code')->default(true);
            $table->boolean('bank_name')->default(true);
            $table->boolean('national_identification_no')->default(true);
            $table->boolean('local_identification_no')->default(true);
            $table->boolean('rte')->default(true);
            $table->boolean('previous_school_details')->default(true);
            $table->boolean('student_note')->default(true);
            $table->boolean('upload_documents')->default(true);
            $table->boolean('staff_designation')->default(true);
            $table->boolean('staff_department')->default(true);
            $table->boolean('staff_last_name')->default(true);
            $table->boolean('staff_father_name')->default(true);
            $table->boolean('staff_mother_name')->default(true);
            $table->boolean('staff_date_of_joining')->default(true);
            $table->boolean('staff_phone')->default(true);
            $table->boolean('staff_emergency_contact')->default(true);
            $table->boolean('staff_marital_status')->default(true);
            $table->boolean('staff_photo')->default(true);
            $table->boolean('staff_current_address')->default(true);
            $table->boolean('staff_permanent_address')->default(true);
            $table->boolean('staff_qualification')->default(true);
            $table->boolean('staff_work_experience')->default(true);
            $table->boolean('staff_note')->default(true);
            $table->boolean('staff_epf_no')->default(true);
            $table->boolean('staff_basic_salary')->default(true);
            $table->boolean('staff_contract_type')->default(true);
            $table->boolean('staff_work_shift')->default(true);
            $table->boolean('staff_work_location')->default(true);
            $table->boolean('staff_leaves')->default(true);
            $table->boolean('staff_account_details')->default(true);
            $table->boolean('staff_social_media')->default(true);
            $table->boolean('staff_upload_documents')->default(true);
            $table->text('mobile_api_url');
            $table->string('app_primary_color_code', 20)->nullable();
            $table->string('app_secondary_color_code', 20)->nullable();
            $table->string('app_logo', 250)->nullable();
            $table->boolean('student_profile_edit')->default(false);
            $table->string('start_week', 10);
            $table->boolean('my_question')->default(false);
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
        Schema::dropIfExists('global_settings');
    }
};
