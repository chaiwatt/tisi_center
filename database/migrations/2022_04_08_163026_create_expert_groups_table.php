<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpertGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_expert_groups', function (Blueprint $table) {
            $table->increments('id')->comment('รหัสตาราง');
            $table->string('title',255)->nullable()->comment('ชื่อข้อมูลความเชี่ยวชาญ');
            $table->boolean('state')->nullable()->comment('สถานะ (เปิด/ปิด');
            $table->integer('ordering')->comment('ลำดับ');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้อัพเดท');
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
        Schema::dropIfExists('basic_expert_groups');
    }
}
