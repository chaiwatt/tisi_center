<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSsoAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_agent', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('ID TB:sso_users.id');
            $table->string('user_taxid', 50)->nullable()->comment('ID TB:sso_users.usersname');
            $table->integer('agent_id')->nullable()->comment('ID TB:sso_users.id');
            $table->string('user_agent', 50)->nullable()->comment('ID TB:sso_users.usersname');
            $table->enum('selcet_all', ['1', '2'])->nullable()->comment('เงื่อนไขการเลือกระบบทั้งหมด (1-ทั้งหมด,2-ไม่เลือกทั้งหมด)');
            $table->enum('issue_type', ['1', '2'])->default('2')->comment('ประเภทการกำหนด (1-ตลอดไป,2-ตามกำหนด)');
            $table->date('start_date')->nullable()->comment('วันที่มีผลมอบอำนาจ');
            $table->date('end_date')->nullable()->comment('สิ้นสุดการมอบอำนาจ');
            $table->boolean('state')->nullable()->comment('สถานะ 1.-มอบอำนาจ, 2.-ดำเนินการตามรับมอบ, 3.-สิ้นสุดการมอบอำนาจ');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('sso_agent');
    }
}
