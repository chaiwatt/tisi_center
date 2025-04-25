<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawNotifyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_notify_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_register')->nullable()->comment('runrecno ตาราง user_register');
            $table->string('name',255)->nullable()->comment('ชื่อ');
            $table->string('email',255)->nullable()->comment('รายชื่ออีเมลส่งถึง (json)');
            $table->integer('read_type')->nullable()->comment('1.อ่านแล้ว, 2.ยังไม่อ่าน');
            $table->integer('marked')->nullable()->comment('1.ติดตาม');
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
        Schema::dropIfExists('law_notify_user');
    }
}
