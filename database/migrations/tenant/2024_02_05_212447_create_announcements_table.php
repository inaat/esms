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
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 128);
            $table->string('description', 1024);
            $table->string('table_type')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->unsignedInteger('session_id')->index('announcements_session_id_foreign');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['table_type', 'table_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
    }
};
