<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToLawCasesDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases_delivery', function (Blueprint $table) {
            $table->integer('noti_sytem_status')->nullable()->comment('ช่องทางการแจ้งเตือน : ระบบ');
            $table->integer('noti_email_status')->nullable()->comment('ช่องทางการแจ้งเตือน : เมล');
            $table->string('send_mail_status')->nullable()->comment('สถานะแจ้งเตือนอีเมล');
            $table->string('noti_email')->nullable()->comment('อีเมลที่แจ้งเตือน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases_delivery', function (Blueprint $table) {
            $table->dropColumn(['noti_email_status','noti_sytem_status','send_mail_status','noti_email']);
            
        });
    }
}
