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
        Schema::table('front_gallery_contents', function (Blueprint $table) {
            $table->foreign(['category_id'], 'front_galleries_content_category_id_foreign')->references(['id'])->on('front_gallery_categories')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('front_gallery_contents', function (Blueprint $table) {
            $table->dropForeign('front_galleries_content_category_id_foreign');
        });
    }
};
