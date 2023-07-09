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
        Schema::table('student_documents', function (Blueprint $table) {
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_documents', function (Blueprint $table) {
            $table->dropForeign('student_documents_student_id_foreign');
        });
    }
};
