<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToLawTrackReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->text('remarks')->nullable()->comment('หมายเหตุ');
            $table->integer('noti_sytem_status')->nullable()->comment('ช่องทางการแจ้งเตือน : ระบบ');
            $table->integer('noti_email_status')->nullable()->comment('ช่องทางการแจ้งเตือน : เมล');
            $table->string('send_mail_status')->nullable()->comment('สถานะแจ้งเตือนอีเมล');
            $table->string('noti_email')->nullable()->comment('อีเมลที่แจ้งเตือน');

            $table->date('accept_date')->nullable()->comment('วันที่รับเรื่อง');
            $table->integer('accept_by')->nullable()->comment('ผู้รับเรื่อง');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->dropColumn(['accept_date','accept_by', 'remarks', 'noti_email_status','noti_sytem_status','send_mail_status','noti_email']);
            
        });
    }
}
