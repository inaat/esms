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
        Schema::create('barcodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('width', 22, 4)->nullable();
            $table->double('height', 22, 4)->nullable();
            $table->double('paper_width', 22, 4)->nullable();
            $table->double('paper_height', 22, 4)->nullable();
            $table->double('top_margin', 22, 4)->nullable();
            $table->double('left_margin', 22, 4)->nullable();
            $table->double('row_distance', 22, 4)->nullable();
            $table->double('col_distance', 22, 4)->nullable();
            $table->integer('stickers_in_one_row')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_continuous')->default(false);
            $table->integer('stickers_in_one_sheet')->nullable();
            $table->unsignedInteger('business_id')->nullable();
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
        Schema::dropIfExists('barcodes');
    }
};
