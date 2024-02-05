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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assignment_id')->index('assignment_submissions_assignment_id_foreign');
            $table->unsignedInteger('student_id')->index('assignment_submissions_student_id_foreign');
            $table->unsignedInteger('session_id')->index('assignment_submissions_session_id_foreign');
            $table->text('feedback')->nullable();
            $table->integer('points')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = Pending/In Review , 1 = Accepted , 2 = Rejected , 3 = Resubmitted');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
