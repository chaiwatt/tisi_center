<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMailToSection5ApplicationInspectorsAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_inspectors_audit', function (Blueprint $table) {
            $table->integer('approve_send_mail_status')->nullable()->comment('สถานะแจ้งเตือนอีเมล');
            $table->text('approve_noti_email')->nullable()->comment('อีเมลที่แจ้งผล');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_inspectors_audit', function (Blueprint $table) {
            $table->dropColumn(['approve_send_mail_status','approve_noti_email']);
            
        });
    }
}
