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
        Schema::table('student_guardians', function (Blueprint $table) {
            $table->foreign(['guardian_id'])->references(['id'])->on('guardians')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_guardians', function (Blueprint $table) {
            $table->dropForeign('student_guardians_guardian_id_foreign');
            $table->dropForeign('student_guardians_student_id_foreign');
        });
    }
};
