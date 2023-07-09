<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    //     Schema::create('student_documents', function (Blueprint $table) {
    //         $table->increments('id');


	// 		$table->unsignedInteger("student_id");
    //         $table->foreign('student_id')
    //         ->references('id')->on('students')
    //         ->onUpdate('cascade')
    //         ->onDelete('cascade');
    //   		$table->string('type',100);
    //   		$table->string('filename',100);


	// 		$table->timestamps();
    //     });
     }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_documents');
    }
}
