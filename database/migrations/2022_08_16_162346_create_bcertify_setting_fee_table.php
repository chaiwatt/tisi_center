<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcertifySettingFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_setting_fee', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fee_name',255)->nullable()->comment('ประเภทค่าธรรมเนีบม');
            $table->string('fee_ref',255)->nullable()->comment('ชื่ออ้างอิง');
            $table->decimal('fee_ib',10,2)->nullable()->comment('หน่วยตรวจ (IB)');
            $table->decimal('fee_cb',10,2)->nullable()->comment('หน่วยรับรอง (CB)');
            $table->decimal('fee_lab',10,2)->nullable()->comment('ห้องปฏิบัติ (LAB)');
            $table->date('fee_start')->nullable()->comment('วันที่มีผลใช้งาน');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('bcertify_setting_fee');
    }
}
