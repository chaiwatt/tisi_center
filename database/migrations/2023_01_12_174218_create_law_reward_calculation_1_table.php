<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawRewardCalculation1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_reward_calculation_1', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_reward_id')->nullable()->comment('ID : law_rewards');
            $table->integer('basic_division_category_id')->nullable()->comment('ID : law_basic_division_category');
            $table->string('division_name',255)->nullable()->comment('ชื่อรายการ'); 
            $table->integer('cal_type')->nullable()->comment('คำนวณเงิน ประเภทการคำนาณ 1.สัดส่วน(%), 2.จำนวนเงิน');
            $table->integer('division')->nullable()->comment('คำนวณเงิน : สัดส่วน(%)');
            $table->decimal('amount',30,2)->nullable()->comment('คำนวณเงิน : จำนวนเงิน');
            $table->decimal('max',30,2)->nullable()->comment('คำนวณเงิน : เพดาน');
            $table->decimal('difference',30,2)->nullable()->comment('ส่วนต่าง');
            $table->decimal('total',30,2)->nullable()->comment('ยอดรวม');
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
        Schema::dropIfExists('law_reward_calculation_1');
    }
}
