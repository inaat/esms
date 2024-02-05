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
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('whatsapp_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('to', 255)->nullable();
            $table->dateTime('initiated_time')->nullable();
            $table->text('message')->nullable();
            $table->string('document', 255)->nullable();
            $table->string('audio', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('video', 299)->nullable();
            $table->tinyInteger('status')->nullable()->default(0)->comment('Pending : 1, Schedule : 2 Fail : 3 Success: 4');
            $table->tinyInteger('schedule_status')->nullable()->default(1)->comment('Send Now : 1, Send Later : 2');
            $table->string('response_gateway', 259)->nullable();
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
        Schema::dropIfExists('whatsapp_logs');
    }
};
