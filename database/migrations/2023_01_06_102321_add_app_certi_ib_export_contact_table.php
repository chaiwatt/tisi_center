<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiIbExportContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->string('certificate_period',255)->nullable()->comment('ระยะเวลาของใบรับรอง');
            $table->string('contact_name',255)->nullable()->comment('ชื่อผู้ติดต่อ');
            $table->string('contact_tel',255)->nullable()->comment('เบอร์โทรผู้ติดต่อ');
            $table->string('contact_moblie',255)->nullable()->comment('เบอร์มือถือผู้ติดต่อ');
            $table->string('contact_email',255)->nullable()->comment('e-Mail ผู้ติดต่อ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->dropColumn(['certificate_period', 'contact_name', 'contact_tel', 'contact_moblie', 'contact_email']); 
        });
    }
}
