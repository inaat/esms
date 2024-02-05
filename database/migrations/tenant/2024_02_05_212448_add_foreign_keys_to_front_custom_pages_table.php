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
        Schema::table('front_custom_pages', function (Blueprint $table) {
            $table->foreign(['front_page_navbar_id'])->references(['id'])->on('front_custom_page_navbars')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('front_custom_pages', function (Blueprint $table) {
            $table->dropForeign('front_custom_pages_front_page_navbar_id_foreign');
        });
    }
};
