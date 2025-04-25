<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawRewardWithdrawsDetailsSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_reward_withdraws_details_sub', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('withdraws_id')->nullable()->comment('เบิกเงินรางวัล ID : law_reward_withdraws');
            $table->integer('withdraws_details_id')->nullable()->comment('รายละเอียดเบิกเงินรางวัล ID : law_reward_withdraws');
            $table->integer('law_reward_recepts_id')->nullable()->comment('ใบสำคัญรับเงิน ID : law_reward_recepts');
            $table->string('name',255)->nullable()->comment('ชื่อสิทธิ์');
            $table->integer('law_basic_reward_group_id')->nullable()->comment('ID : law_basic_reward_group');
            $table->decimal('amount',30,2)->nullable()->comment('จำนวนเงิน');
            $table->integer('status')->nullable()->comment('สถานะ 1.ขอรับเงิน, 2.ไม่ขอรับเงิน');
            $table->text('remark')->nullable()->comment('หมายเหตุ'); 
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
        Schema::dropIfExists('law_reward_withdraws_details_sub');
    }
}
