<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawRewardWithdrawsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_reward_withdraws_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('withdraws_id')->nullable()->comment('เบิกเงินรางวัล ID : law_reward_withdraws');
            $table->string('case_number',255)->nullable()->comment('เลขคดี');
            $table->integer('income_number')->nullable()->comment('จำนวนผู้มีสิทธิ์/ราย');
            $table->decimal('amount',30,2)->nullable()->comment('จำนวนเงิน');
            $table->text('remark')->nullable()->comment('หมายเหตุ'); 
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก ID : user_register');
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
        Schema::dropIfExists('law_reward_withdraws_details');
    }
}
