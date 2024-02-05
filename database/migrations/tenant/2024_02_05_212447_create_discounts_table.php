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
        Schema::create('discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('discount_name');
            $table->unsignedInteger('system_settings_id')->index('discounts_system_settings_id_foreign');
            $table->unsignedInteger('campus_id')->nullable()->index('discounts_campus_id_foreign');
            $table->string('discount_type')->nullable();
            $table->decimal('discount_amount', 22, 4)->default(0);
            $table->unsignedInteger('created_by')->index('discounts_created_by_foreign');
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
        Schema::dropIfExists('discounts');
    }
};
