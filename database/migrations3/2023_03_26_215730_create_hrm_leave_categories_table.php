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
        Schema::create('hrm_leave_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('leave_category', 100)->unique();
            $table->integer('max_leave_count')->nullable();
            $table->enum('leave_count_interval', ['month', 'year'])->nullable();
            $table->unsignedInteger('created_by')->index('hrm_leave_categories_created_by_foreign');
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
        Schema::dropIfExists('hrm_leave_categories');
    }
};
