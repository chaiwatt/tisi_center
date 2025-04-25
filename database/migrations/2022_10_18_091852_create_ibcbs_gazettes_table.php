<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbcbsGazettesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_ibcbs_gazettes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ibcb_id')->nullable();
            $table->string('ibcb_code',255)->nullable()->comment('รหัสหน่วยตรวจสอบ');

            $table->string('issue')->nullable()->comment('ฉบับที่');
            $table->string('year')->nullable()->comment('ปีที่ประกาศ');
            $table->date('announcement_date')->nullable()->comment('ประกาศ ณ วันที่');
            $table->date('government_gazette_date')->nullable()->comment('วันที่ประกาศราชกิจจา');
            $table->text('government_gazette_description')->nullable()->comment('รายละเอียด/หมายเหตุ');

            $table->string('sign_id')->nullable()->comment('ผู้ลงนาม');
            $table->text('sign_name')->nullable()->comment('ชื่อผู้ลงนาม');
            $table->text('sign_position')->nullable()->comment('ตำแหน่ง');

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
        Schema::dropIfExists('section5_ibcbs_gazettes');
    }
}
