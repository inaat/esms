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
        Schema::create('guardians', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guardian_name');
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_cnic', 250)->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_address')->nullable();
            $table->string('guardian_image')->nullable();
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
        Schema::dropIfExists('guardians');
    }
};
