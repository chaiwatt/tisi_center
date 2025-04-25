<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawListenMinistriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_listen_ministry', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref_no')->nullable()->comment('เลขอ้างอิง');
            $table->string('title',255)->nullable()->comment('ชื่อเรื่อง');        
            $table->string('tis_name',255)->nullable()->comment('ชื่อมาตรฐาน');
            $table->string('tis_no',255)->nullable()->comment('เลข มอก.');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->string('dear',255)->nullable()->comment('เรียน(ชื่อในเมล)');
            $table->integer('url_type')->nullable()->comment('ประเภทลิงค์ที่จะให้เข้ามาทำแบบตอบรับความเห็น 1=ดึงจากระบบ, 2=ระบุเอง');   
            $table->date('date_due')->nullable()->comment('วันที่ครบกำหนดรับฟังความเห็น');
            $table->date('date_start')->nullable()->comment('วันที่ประกาศ');
            $table->date('date_end')->nullable()->comment('วันที่ปิดประกาศ');
            $table->integer('mail_status')->nullable()->comment('1=ส่งอีเมล, 2=ไม่ส่งเมล');   
            $table->text('mail_list')->nullable()->comment('เมลผู้รับ');
            $table->integer('status_id')->nullable()->comment('สถานะแบบรับฟังความเห็นร่างกฎกระทรวง');
            $table->boolean('state')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('law_listen_ministry');
    }
}
