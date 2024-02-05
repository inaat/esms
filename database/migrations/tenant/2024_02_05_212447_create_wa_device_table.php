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
        Schema::create('wa_device', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('number', 259);
            $table->string('name', 259);
            $table->string('description', 259);
            $table->string('delay_time', 259)->nullable();
            $table->enum('status', ['connected', 'disconnected', 'initiate']);
            $table->enum('multidevice', ['YES', 'NO']);
            $table->string('sms_status', 52)->nullable()->default('sms_on');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wa_device');
    }
};
