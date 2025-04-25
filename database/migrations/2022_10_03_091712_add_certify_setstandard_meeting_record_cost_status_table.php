<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifySetstandardMeetingRecordCostStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_setstandard_meeting_record_cost', function (Blueprint $table) {
            $table->integer('status')->nullable()->comment('สถานะการประชุม แยกการกำหนดมาตรฐาน 1.ผ่าน 2.ไม่ผ่านปรับปรุงแก้ไขตามวาระ 3.ยกเลิก');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_setstandard_meeting_record_cost', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
}
