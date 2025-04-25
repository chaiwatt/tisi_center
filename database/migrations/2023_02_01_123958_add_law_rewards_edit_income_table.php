<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawRewardsEditIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_rewards', function (Blueprint $table) {
            $table->enum('edit_income', array('0','1'))->default('0')->comment('แก้ไขสัดส่วนเงินคำนวณ(ส่วนที่ 1 : คำนวณเงินหักเป็นรายได้แผ่นดิน) 1.แก้ไข, 2.ไม่แก้ไข')->after('status');
            $table->enum('edit_proportion', array('0','1'))->default('0')->comment('แก้ไขสัดส่วนเงินคำนวณ(ส่วนที่ 2 : คำนวณสัดส่วนเงินสินบน / เงินรางวัล / ค่าใช้จ่ายในการดำเนิน) 1.แก้ไข, 2.ไม่แก้ไข')->after('edit_income');
            $table->integer('law_config_reward_id')->nullable()->comment('กลุ่มผู้มีสิทธิ์ฯ ID : law_config_reward');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_rewards', function (Blueprint $table) {
            $table->dropColumn(['edit_income','edit_proportion','law_config_reward_id']);
        });
    }
}
