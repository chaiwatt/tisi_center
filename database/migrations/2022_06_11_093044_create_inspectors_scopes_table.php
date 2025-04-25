<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectorsScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_inspectors_scopes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inspectors_id')->nullable();
            $table->string('inspectors_code', 255)->nullable()->comment('รหัสผู้ตรวจ/ผู้ประเมิน');
            $table->integer('branch_id')->nullable()->comment('ไอดีรายสาขา');
            $table->integer('branch_group_id')->nullable()->comment('ไอดีหมวดอุตสาหกรรม/สาขา');
            $table->string('agency_taxid',13)->nullable()->comment('เลขนิติบุคคลผู้เสียภาษี');
            $table->date('start_date')->nullable()->comment('วันที่เริ่มต้น');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุด');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 2 = Not Active');
            $table->string('ref_inspector_application_no', 255)->nullable()->comment('อ้างอิงเลขที่คำขอ');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
            $table->foreign('inspectors_id')
                    ->references('id')
                    ->on('section5_inspectors')
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
        Schema::dropIfExists('section5_inspectors_scopes');
    }
}
