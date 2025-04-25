<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawConfigRewardMaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_config_reward_max', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_config_section_id')->nullable()->comment('ID : law_config_section');
            $table->integer('law_basic_arrest_id')->nullable()->comment('ID : law_basic_arrest');
            $table->string('condition_percentage')->nullable()->comment('เงื่อนไขเปอร์เซ็นเงินที่หักได้ : เท่ากับ เก็บ =, ไม่เท่า เก็บ <=, เกิน เก็บ >=');
            $table->tinyInteger('amount')->nullable()->comment('เงื่อนไขเปอร์เซ็นเงินที่หักได้');
            $table->string('condition_money')->nullable()->comment('เงื่อนไขจำนวนเงิน : เท่ากับ เก็บ =, ไม่เท่า เก็บ <=, เกิน เก็บ >=');
            $table->decimal('money',12,2)->nullable()->comment('จำนวนเงิน');
            $table->boolean('state')->nullable()->comment('สถานะใช้งาน');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->foreign('law_config_section_id')
                    ->references('id')
                    ->on('law_config_section')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('law_config_reward_max');
    }
}
