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
        Schema::create('assessment_sub_headings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('heading_id')->index('assessment_sub_headings_heading_id_foreign');
            $table->unsignedInteger('created_by')->index('assessment_sub_headings_created_by_foreign');
            $table->softDeletes();
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
        Schema::dropIfExists('assessment_sub_headings');
    }
};
