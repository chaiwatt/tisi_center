<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawBasicRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_config_reward', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable()->comment('ชื่อกลุ่มที่กำหนด');
            $table->unsignedInteger('reward_group_id')->nullable()->comment('id ตาราง law_basic_reward_group');
            $table->unsignedInteger('arrest_id')->nullable()->comment('id ตาราง law_basic_arrest');
            $table->unsignedInteger('operation_id')->nullable()->comment('1=เปรียบเทียบปรับ, 2=ส่งดำเนินคดี');
            $table->tinyInteger('amount')->default(0)->comment('จำนวนเปอร์เซ็นเงินรางวัลที่จะได้รับ');
            $table->tinyInteger('state')->default(1)->comment('สถานะการใช้งาน 1=ใช้งาน, 0=ไม่ใช้งาน');
            $table->integer('created_by')->nullable()->comment('ผู้สร้าง runrecno ตาราง user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข runrecno ตาราง user_register');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `law_config_reward` comment 'ตาราง กำหนดอัตราโทษตามมาตราความผิด'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('law_config_reward');
    }
}
