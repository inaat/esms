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
        Schema::create('classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->decimal('tuition_fee', 22, 4)->default(0);
            $table->decimal('admission_fee', 22, 4)->default(0);
            $table->decimal('transport_fee', 22, 4)->default(0);
            $table->decimal('security_fee', 22, 4)->default(0);
            $table->decimal('prospectus_fee', 22, 4)->nullable()->default(0);
            $table->unsignedInteger('system_settings_id')->index('classes_system_settings_id_foreign');
            $table->unsignedInteger('campus_id')->nullable()->index('classes_campus_id_foreign');
            $table->unsignedInteger('class_level_id')->nullable()->index('classes_class_level_id_foreign');
            $table->unsignedInteger('created_by')->index('classes_created_by_foreign');
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
        Schema::dropIfExists('classes');
    }
};
