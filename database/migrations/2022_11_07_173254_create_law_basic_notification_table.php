<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawBasicNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_config_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable()->comment('ชื่อเรื่อง');
            $table->string('color')->nullable()->comment('สี เก็บชื่อ class css : danger, warning, success');
            $table->string('condition')->nullable()->comment('เงื่อนไข < = >');
            $table->tinyInteger('amount')->default(0)->comment('จำนวน(วัน)');
            $table->tinyInteger('state')->default(1)->comment('สถานะการใช้งาน 1=ใช้งาน, 0=ไม่ใช้งาน');
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
        Schema::dropIfExists('law_config_notification');
    }
}
