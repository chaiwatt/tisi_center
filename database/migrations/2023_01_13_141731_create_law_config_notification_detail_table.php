<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawConfigNotificationDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_config_notification_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_config_notification_id')->nullable()->comment('id ตาราง law_config_notification');
            $table->string('color')->nullable()->comment('สี เก็บชื่อ class css : danger, warning, success');
            $table->string('condition')->nullable()->comment('เงื่อนไข < = >');
            $table->tinyInteger('amount')->default(0)->comment('จำนวน(วัน)');
            $table->integer('created_by')->nullable()->comment('ผู้สร้าง runrecno ตาราง user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข runrecno ตาราง user_register');
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
        Schema::dropIfExists('law_config_notification_detail');
    }
}
