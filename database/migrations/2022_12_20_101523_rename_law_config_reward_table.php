<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLawConfigRewardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_config_reward', function (Blueprint $table) {
            $table->dropColumn(['reward_group_id','amount']);
            $table->text('operation_id')->comment('การดำเนินการ (json) 1=ทุกกรณี, 2=เปรียบเทียบปรับ, 3=ส่งดำเนินคดี')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_config_reward', function (Blueprint $table) {
            $table->unsignedInteger('reward_group_id')->nullable()->comment('id ตาราง law_basic_reward_group')->after('title');
            $table->tinyInteger('amount')->default(0)->comment('จำนวนเปอร์เซ็นเงินรางวัลที่จะได้รับ')->after('operation_id');
            $table->integer('operation_id')->comment('1=เปรียบเทียบปรับ, 2=ส่งดำเนินคดี')->change();
        });
    }
}
