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
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('system_settings_id')->index('accounts_system_settings_id_foreign');
            $table->unsignedInteger('campus_id')->nullable()->index('accounts_campus_id_foreign');
            $table->boolean('default_campus_account')->default(false);
            $table->string('name');
            $table->string('account_number');
            $table->integer('account_type_id')->nullable();
            $table->text('note')->nullable();
            $table->unsignedInteger('created_by')->index('accounts_created_by_foreign');
            $table->boolean('is_closed')->default(false);
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
        Schema::dropIfExists('accounts');
    }
};
