<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcceptChkToEsurvApplicant21tersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esurv_applicant_21ters', function (Blueprint $table) {
            $table->string('accept_chk', 1)->default(0)->comment('0 ไม่ยอมรับ 1 ยอมรับ');
            $table->integer('state_off_confirm_by')->nullable()->comment('ผู้ยืนยันปิดสถานะคำขอ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esurv_applicant_21ters', function (Blueprint $table) {
            $table->dropColumn(['accept_chk', 'state_off_confirm_by']);
        });
    }
}
