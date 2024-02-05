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
        Schema::create('exam_creates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exam_term_id')->index('exam_creates_exam_term_id_foreign');
            $table->unsignedInteger('session_id')->index('exam_creates_session_id_foreign');
            $table->unsignedInteger('campus_id')->index('exam_creates_campus_id_foreign');
            $table->enum('roll_no_type', ['default_roll_no', 'custom_roll_no']);
            $table->enum('order_type', ['descending', 'ascending'])->nullable();
            $table->integer('start_from')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->tinyInteger('publish')->default(0);
            $table->text('class_ids')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('exam_creates');
    }
};
