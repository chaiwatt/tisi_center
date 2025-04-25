<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiLogEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_log_email', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_no',255)->nullable()->comment('เลขที่คำขอ');
            $table->integer('app_id')->nullable()->comment('ID คำขอ');
            $table->text('app_table')->nullable()->comment('TB คำขอ');
            $table->integer('ref_id')->nullable()->comment('ID ระบบ');
            $table->text('ref_table')->nullable()->comment('TB ระบบ');
            $table->integer('certify')->nullable('1.LAB, 2.IB, 3.CB, 4.LAB(ต่อตาม), 5.IB(ต่อตาม), 6.CB(ต่อตาม)');
            $table->text('subject')->nullable()->comment('ชื่อเรื่อง');
            $table->longText('detail')->nullable()->comment('รายละเอียด');
            $table->text('email')->nullable()->comment('email');
            $table->text('email_to')->nullable()->comment('to');
            $table->text('email_cc')->nullable()->comment('cc');
            $table->text('email_reply')->nullable()->comment('reply');
            $table->integer('status')->nullable('1.ส่งสำเร็จ , 2.ส่งไม่สำเร็จ');
            $table->text('attach')->nullable('ไฟล์แนบ');
            $table->integer('user_id')->nullable()->comment('ผู้บันทึก (ผปก.) TB : sso_users');
            $table->integer('agent_id')->nullable()->comment('มอบหมาย (ผปก.) TB : sso_users');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก (จนท.) TB : user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('app_certi_log_email');
    }
}
