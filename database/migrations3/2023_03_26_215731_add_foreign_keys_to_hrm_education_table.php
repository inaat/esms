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
        Schema::table('hrm_education', function (Blueprint $table) {
            $table->foreign(['created_by'], 'hrm_educations_created_by_foreign')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hrm_education', function (Blueprint $table) {
            $table->dropForeign('hrm_educations_created_by_foreign');
        });
    }
};
