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
        Schema::create('online_applicants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campus_id')->nullable()->index('online_applicants_campus_id_foreign');
            $table->unsignedInteger('adm_session_id')->nullable()->index('online_applicants_adm_session_id_foreign');
            $table->unsignedInteger('adm_class_id')->index('online_applicants_adm_class_id_foreign');
            $table->unsignedInteger('domicile_id')->nullable()->index('online_applicants_domicile_id_foreign');
            $table->string('online_applicant_no')->unique();
            $table->date('applicant_submit_date');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('gender', ['male', 'female', 'others']);
            $table->date('birth_date');
            $table->unsignedInteger('country_id')->nullable()->index('online_applicants_country_id_foreign');
            $table->unsignedInteger('province_id')->nullable()->index('online_applicants_province_id_foreign');
            $table->unsignedInteger('district_id')->nullable()->index('online_applicants_district_id_foreign');
            $table->unsignedInteger('city_id')->nullable()->index('online_applicants_city_id_foreign');
            $table->text('std_current_address')->nullable();
            $table->text('std_permanent_address')->nullable();
            $table->boolean('is_kmu_cat');
            $table->string('previous_college_name');
            $table->string('fsc_roll_no');
            $table->string('board_name');
            $table->string('fsc_marks');
            $table->string('father_name');
            $table->string('father_phone')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_cnic_no')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->text('medical_history')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email')->unique();
            $table->string('cnic_no');
            $table->enum('blood_group', ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'])->nullable();
            $table->string('nationality')->nullable();
            $table->enum('status', ['online_admission', 'confirm', 'reject', 'hold'])->default('online_admission');
            $table->enum('religion', ['Islam', 'Hinduism', 'Christianity', 'Sikhism', 'Buddhism', 'Secular/Nonreligious/Agnostic/Atheist', 'Other']);
            $table->string('student_image', 250)->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->text('guardian_address')->nullable();
            $table->text('fsc_mark_sheet')->nullable();
            $table->text('cnic_front_side')->nullable();
            $table->text('cnic_back_side')->nullable();
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
        Schema::dropIfExists('online_applicants');
    }
};
