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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('org_name');
            $table->text('org_short_name')->nullable();
            $table->string('org_address');
            $table->string('org_contact_number');
            $table->text('org_email')->nullable();
            $table->text('org_website')->nullable();
            $table->string('org_logo');
            $table->text('tag_line')->nullable();
            $table->text('page_header_logo')->nullable();
            $table->text('id_card_logo')->nullable();
            $table->string('org_favicon');
            $table->unsignedInteger('currency_id')->index('system_settings_currency_id_foreign');
            $table->enum('currency_symbol_placement', ['before', 'after'])->default('before');
            $table->date('start_date')->nullable();
            $table->string('date_format')->default('m/d/Y');
            $table->enum('time_format', ['12', '24'])->default('24');
            $table->string('time_zone')->default('Asia/Karachi');
            $table->string('start_month', 40);
            $table->unsignedInteger('transaction_edit_days')->default(30);
            $table->text('email_settings')->nullable();
            $table->text('sms_settings')->nullable();
            $table->text('ref_no_prefixes')->nullable();
            $table->boolean('enable_tooltip')->default(true);
            $table->char('theme_color', 20)->nullable();
            $table->text('common_settings')->nullable();
            $table->timestamps();
            $table->text('pdf')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
};
