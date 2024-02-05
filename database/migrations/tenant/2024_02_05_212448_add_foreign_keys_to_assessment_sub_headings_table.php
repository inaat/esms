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
        Schema::table('assessment_sub_headings', function (Blueprint $table) {
            $table->foreign(['created_by'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['heading_id'])->references(['id'])->on('assessment_headings')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_sub_headings', function (Blueprint $table) {
            $table->dropForeign('assessment_sub_headings_created_by_foreign');
            $table->dropForeign('assessment_sub_headings_heading_id_foreign');
        });
    }
};
