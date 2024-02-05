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
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable()->index('students_user_id_foreign');
            $table->decimal('advance_amount', 22, 4)->default(0);
            $table->unsignedInteger('campus_id')->nullable()->index('students_campus_id_foreign');
            $table->unsignedInteger('adm_session_id')->nullable()->index('students_adm_session_id_foreign');
            $table->unsignedInteger('cur_session_id')->nullable()->index('students_cur_session_id_foreign');
            $table->string('admission_no')->unique();
            $table->date('admission_date');
            $table->string('roll_no')->unique();
            $table->text('old_roll_no')->nullable();
            $table->unsignedInteger('adm_class_id')->index('students_admission_class_id_foreign');
            $table->unsignedInteger('current_class_id')->index('students_current_class_id_foreign');
            $table->unsignedInteger('adm_class_section_id')->index('students_adm_class_section_id_foreign');
            $table->unsignedInteger('current_class_section_id')->index('students_current_class_section_id_foreign');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('birth_date');
            $table->string('BirthPlace', 250)->nullable();
            $table->string('caste', 155)->nullable();
            $table->string('height', 155)->nullable();
            $table->string('weight', 155)->nullable();
            $table->unsignedInteger('category_id')->nullable()->index('students_category_id_foreign');
            $table->unsignedInteger('domicile_id')->nullable()->index('students_domicile_id_foreign');
            $table->enum('religion', ['Islam', 'Hinduism', 'Christianity', 'Sikhism', 'Buddhism', 'Secular/Nonreligious/Agnostic/Atheist', 'Other'])->default('Islam');
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->string('cnic_no')->nullable();
            $table->enum('blood_group', ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'])->nullable();
            $table->string('nationality')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('student_image')->nullable()->default('default.png');
            $table->text('medical_history')->nullable();
            $table->enum('status', ['active', 'inactive', 'pass_out', 'struck_up', 'took_slc'])->default('active');
            $table->string('father_name');
            $table->string('father_phone')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_cnic_no')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_cnic_no')->nullable();
            $table->unsignedInteger('country_id')->nullable()->index('students_country_id_foreign');
            $table->unsignedInteger('province_id')->nullable()->index('students_province_id_foreign');
            $table->unsignedInteger('district_id')->nullable()->index('students_district_id_foreign');
            $table->unsignedInteger('city_id')->nullable()->index('students_city_id_foreign');
            $table->unsignedInteger('region_id')->nullable()->index('students_region_id_foreign');
            $table->text('std_current_address')->nullable();
            $table->text('std_permanent_address')->nullable();
            $table->text('remark')->nullable();
            $table->string('previous_school_name', 250)->nullable();
            $table->string('last_grade', 250)->nullable();
            $table->decimal('student_tuition_fee', 22, 4)->default(0);
            $table->tinyInteger('is_transport')->default(0);
            $table->decimal('student_transport_fee', 22, 4)->default(0);
            $table->unsignedInteger('vehicle_id')->nullable()->index('students_vehicle_id_foreign');
            $table->unsignedInteger('system_settings_id')->index('students_system_settings_id_foreign');
            $table->unsignedInteger('discount_id')->nullable()->index('students_discount_id_foreign');
            $table->decimal('fee_before_discount', 22, 4)->default(0);
            $table->decimal('discount_per', 22, 4)->default(0);
            $table->unsignedInteger('created_by')->index('students_created_by_foreign');
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
        Schema::dropIfExists('students');
    }
};
