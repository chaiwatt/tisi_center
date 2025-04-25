<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawNotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_notify', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_system_category_id')->nullable()->comment('ID : ตาราง law_system_categories');
            $table->string('ref_table',255)->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('ID ตารางของ ref_table');
            $table->string('name_system',255)->nullable()->comment('ชื่อระบบงานย่อย (เก็บ Text)');
            $table->string('title',255)->nullable()->comment('ชื่อเรื่อง');
            $table->text('content')->nullable()->comment('เนื้อหาแจ้งเตือน');
            $table->text('channel')->nullable()->comment('ช่องทางแจ้งเตือน (json)');
            $table->integer('notify_type')->nullable()->comment('1.เจ้าหน้าที่, 2.ผู้ประสานงาน (เจ้าของคดี), 2.ผู้ประสานงาน (กระทำความคิด), 4.ผู้มอบหมายงาน (ผก.), 5.ผู้บริการ (ผอ.)');
            $table->bigInteger('created_by')->comment('ผู้บันทึก');
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
        Schema::dropIfExists('law_notify');
    }
}
