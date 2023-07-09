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
        Schema::create('certificate_issues', function (Blueprint $table) {
            $table->increments('id');
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('certificate_no')->nullable();
            $table->unsignedInteger('certificate_type_id')->index('certificate_issues_certificate_type_id_foreign');
            $table->unsignedInteger('student_id')->index('certificate_issues_student_id_foreign');
            $table->unsignedInteger('campus_id')->index('certificate_issues_campus_id_foreign');
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
        Schema::dropIfExists('certificate_issues');
    }
};
