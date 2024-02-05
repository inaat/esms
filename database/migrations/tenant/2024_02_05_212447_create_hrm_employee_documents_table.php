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
        Schema::create('hrm_employee_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id')->index('hrm_employee_documents_employee_id_foreign');
            $table->string('type', 100);
            $table->string('filename', 100);
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
        Schema::dropIfExists('hrm_employee_documents');
    }
};
