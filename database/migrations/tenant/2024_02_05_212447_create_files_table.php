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
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('modal_type');
            $table->unsignedBigInteger('modal_id');
            $table->string('file_name', 1024)->nullable();
            $table->string('file_thumbnail', 1024)->nullable();
            $table->text('type')->comment('1 = File Upload, 2 = Youtube Link, 3 = Video Upload, 4 = Other Link');
            $table->string('file_url', 1024);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['modal_type', 'modal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
