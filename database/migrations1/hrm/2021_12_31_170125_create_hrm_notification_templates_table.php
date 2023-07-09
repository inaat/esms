<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_notification_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_for');
            $table->text('email_body')->nullable();
            $table->text('sms_body')->nullable();
            $table->text('whatsapp_text')->nullable();
            $table->string('subject')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
            $table->boolean('auto_send')->default(0);
            $table->boolean('auto_send_sms')->default(0);
            $table->boolean('auto_send_wa_notif')->default(0);
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
        Schema::dropIfExists('hrm_notification_templates');
    }
}
