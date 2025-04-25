<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbcbsScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_ibcbs_scopes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ibcb_id')->nullable();
            $table->string('ibcb_code',255)->nullable()->comment('รหัสหน่วยตรวจสอบ');
            $table->integer('branch_group_id')->nullable()->comment('ไอดีหมวดอุตสาหกรรม/สาขา');
            $table->string('isic_no')->nullable()->comment('ISIC NO');
            $table->date('start_date')->nullable()->comment('วันที่เริ่มต้น');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุด');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 2 = Not Active');
            $table->string('ref_ibcb_application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
            $table->foreign('ibcb_id')
                    ->references('id')
                    ->on('section5_ibcbs')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section5_ibcbs_scopes');
    }
}
