<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTisiLicensePausesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb4_tisilicense_pauses', function (Blueprint $table) {
            $table->increments('Autono')->comment('รหัสประจำตาราง');

            $table->string('tbl_licenseNo',255)->nullable()->comment('เลขที่ใบอนุญาต');
            $table->text('input_data')->nullable()->comment('ข้อมูลมาจากระบบงาน - law');

            $table->text('case_number')->nullable()->comment('เลขคดี');
            $table->date('date_pause_start')->nullable()->comment('วันเริ่มพักใบอนุญาต');
            $table->date('date_pause_end')->nullable()->comment('พักถึงวันที');
            $table->text('remark')->nullable()->comment('หมายเหตุ (ถ้ามี)');
            $table->text('evidence_file')->nullable()->comment('ไฟล์หลักฐาน');

            $table->date('date_pause_cancel')->nullable()->comment('วันที่ยกเลิกพักใช้ก่อนกำหนด');
            $table->text('remark_pause_cancel')->nullable()->comment('หมายเหตุยกเลิกพักใช้ก่อนกำหนด');

            $table->integer('pause_cancel_by')->nullable()->comment('ผู้บันทึกยกเลิกพักใช้ก่อนกำหนด');
            $table->timestamp('pause_cancel_at')->nullable()->comment('วันที่บันทึกการยกเลิก');

            $table->integer('created_by')->nullable()->comment('ผู้บันทึกพักใบอนุญาต');

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
        Schema::dropIfExists('tb4_tisilicense_pauses');
    }
}
