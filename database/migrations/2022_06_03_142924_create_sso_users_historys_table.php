<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSsoUsersHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_users_historys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable()->comment('id ตาราง sso_users');
            $table->string('data_field')->nullable()->comment('ชื่อฟิลด์');
            $table->text('data_old')->nullable()->comment('ข้อมูลเดิม');
            $table->text('data_new')->nullable()->comment('ข้อมูลใหม่');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->dateTime('created_at')->comment('วันเวลาที่สร้าง');
            $table->integer('created_by')->nullable()->comment('id ตาราง user_register');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sso_users_historys');
    }
}
