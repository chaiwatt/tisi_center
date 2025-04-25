<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawRewardReceptsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_reward_recepts_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('law_reward_recepts_id')->nullable()->comment('ใบสำคัญรับเงิน ID : law_reward_recepts');
            $table->string('law_reward_staff_lists_id')->nullable()->comment('รายชื่อผู้มีสิทธิ์ได้รับเงิน ID : law_reward_staff_lists');
            $table->string('case_number')->nullable()->comment('เลขคดี case_number : law_cases ');
            $table->text('item')->nullable()->comment('รายการ');
            $table->decimal('amount',30,2)->nullable()->comment('จำนวนเงิน');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก ID : user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข ID : user_register');
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
        Schema::dropIfExists('law_reward_recepts_details');
    }
}
