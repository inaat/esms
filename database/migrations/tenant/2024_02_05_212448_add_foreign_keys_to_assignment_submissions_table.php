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
        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->foreign(['assignment_id'])->references(['id'])->on('assignments')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['session_id'])->references(['id'])->on('sessions')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropForeign('assignment_submissions_assignment_id_foreign');
            $table->dropForeign('assignment_submissions_session_id_foreign');
            $table->dropForeign('assignment_submissions_student_id_foreign');
        });
    }
};
