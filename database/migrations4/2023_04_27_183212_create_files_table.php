<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
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
            $table->morphs('modal');
            $table->string('file_name',1024)->nullable();
            $table->string('file_thumbnail',1024)->nullable();
            $table->tinyText('type',64)->comment('1 = File Upload, 2 = Youtube Link, 3 = Video Upload, 4 = Other Link');
            $table->string('file_url',1024);
            $table->timestamps();
            $table->softDeletes();
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
}
