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
        Schema::create('fee_increment_decrements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('session_id')->index('fee_increment_decrement_session_id_foreign');
            $table->unsignedInteger('campus_id')->index('fee_increment_decrement_campus_id_foreign');
            $table->unsignedInteger('class_id')->index('fee_increment_decrement_class_id_foreign');
            $table->unsignedInteger('class_section_id')->nullable()->index('fee_increment_decrement_class_section_id_foreign');
            $table->decimal('tuition_fee', 22, 4)->default(0);
            $table->decimal('transport_fee', 22, 4)->default(0);
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
        Schema::dropIfExists('fee_increment_decrements');
    }
};
