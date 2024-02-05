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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname');
            $table->enum('user_type', ['admin', 'other', 'teacher', 'guardian', 'staff', 'student'])->nullable();
            $table->unsignedInteger('hook_id')->nullable();
            $table->text('fcm_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedInteger('system_settings_id')->nullable()->index('users_system_settings_id_foreign');
            $table->unsignedInteger('campus_id')->nullable();
            $table->text('image')->nullable();
            $table->string('language', 250)->default('en');
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
        Schema::dropIfExists('users');
    }
};
