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
        Schema::create('fee_heads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->unsignedInteger('campus_id')->nullable()->index('fee_heads_campus_id_foreign');
            $table->unsignedInteger('class_id')->nullable()->index('fee_heads_class_id_foreign');
            $table->decimal('amount', 22, 4)->default(0);
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
        Schema::dropIfExists('fee_heads');
    }
};
