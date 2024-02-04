<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdmissionColumnFrontSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('front_settings', function (Blueprint $table) {
             $table->enum('admission_open', ['yes','no'])->default('no')->after('facebook_embed');
             $table->string('admission_banner')->nullable()->after('admission_open');
 
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
