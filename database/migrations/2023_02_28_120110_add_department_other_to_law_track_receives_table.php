<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmentOtherToLawTrackReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            
            $table->text('law_bs_deperment_other')->nullable()->comment('หน่วยงานเจ้าของเรื่อง : อื่นๆระบุ')->after('law_bs_deperment_id');
            $table->integer('cancel_status')->nullable()->comment('ยกเลิก  : สถานะ 1.ยกเลิก')->after('close_by');
            $table->text('cancel_remark')->nullable()->comment('ยกเลิก : หมายเหตุ')->after('cancel_status');
            $table->integer('cancel_by')->nullable()->comment('ยกเลิก ID : user_register')->after('cancel_remark');
            $table->dateTime('cancel_at')->nullable()->comment('ยกเลิก  : วันที่บันทึก')->after('cancel_by');
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
            $table->dropColumn([
                                'law_bs_deperment_other',
                                'cancel_status',
                                'cancel_remark',
                                'cancel_by',
                                'cancel_at'
                            ]);
        });
    }
}
