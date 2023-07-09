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
        Schema::table('certificate_issues', function (Blueprint $table) {
            $table->foreign(['certificate_type_id'])->references(['id'])->on('certificate_types')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onDelete('CASCADE');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_issues', function (Blueprint $table) {
            $table->dropForeign('certificate_issues_certificate_type_id_foreign');
            $table->dropForeign('certificate_issues_campus_id_foreign');
            $table->dropForeign('certificate_issues_student_id_foreign');
        });
    }
};
