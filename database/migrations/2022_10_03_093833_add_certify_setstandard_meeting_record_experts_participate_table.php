<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifySetstandardMeetingRecordExpertsParticipateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_setstandard_meeting_record_experts', function (Blueprint $table) {
            $table->integer('participate')->nullable()->comment('การเข้าร่วม (1-เข้าร่วม, 2-ไม่เข้าร่วม');
            $table->text('detail')->nullable()->comment('รายละเอียด');
        });
    }

    /**
     * Reverse the migrations.
     * 
     * @return void
     */
    public function down()
    {
        Schema::table('certify_setstandard_meeting_record_experts', function (Blueprint $table) {
            $table->dropColumn(['participate','detail']);
        });
    }
}
