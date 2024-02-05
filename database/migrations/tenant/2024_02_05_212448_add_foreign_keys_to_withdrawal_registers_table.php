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
        Schema::table('withdrawal_registers', function (Blueprint $table) {
            $table->foreign(['admission_class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['adm_session_id'])->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['campus_id'])->references(['id'])->on('campuses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['leaving_class_id'])->references(['id'])->on('classes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['leaving_session_id'])->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table('withdrawal_registers', function (Blueprint $table) {
            $table->dropForeign('withdrawal_registers_admission_class_id_foreign');
            $table->dropForeign('withdrawal_registers_adm_session_id_foreign');
            $table->dropForeign('withdrawal_registers_campus_id_foreign');
            $table->dropForeign('withdrawal_registers_leaving_class_id_foreign');
            $table->dropForeign('withdrawal_registers_leaving_session_id_foreign');
            $table->dropForeign('withdrawal_registers_student_id_foreign');
        });
    }
};
