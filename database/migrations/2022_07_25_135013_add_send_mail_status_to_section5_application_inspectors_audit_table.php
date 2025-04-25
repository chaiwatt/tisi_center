<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendMailStatusToSection5ApplicationInspectorsAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_inspectors_audit', function (Blueprint $table) {
            $table->integer('send_mail_status')->nullable()->after('audit_remark')->comment('สถานะแจ้งเตือนอีเมล');
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
            $table->dropColumn(['send_mail_status']);
        });
    }
}
