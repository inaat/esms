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
        Schema::create('student_guardians', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id')->nullable()->index('student_guardians_student_id_foreign');
            $table->unsignedInteger('guardian_id')->nullable()->index('student_guardians_guardian_id_foreign');
            $table->timestamps();

            $table->unique(['student_id'], 'student_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_guardians');
    }
};
