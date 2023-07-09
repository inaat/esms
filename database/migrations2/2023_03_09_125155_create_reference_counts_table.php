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
        Schema::create('reference_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref_type');
            $table->integer('ref_count');
            $table->unsignedInteger('session_id')->nullable()->index('reference_counts_session_id_foreign');
            $table->text('session_close')->nullable();
            $table->unsignedInteger('system_settings_id')->index('reference_counts_system_settings_id_foreign');
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
        Schema::dropIfExists('reference_counts');
    }
};
