<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCertifyStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_standards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('setstandard_id')->comment('ID ตารางการกำหนดมาตรฐาน');
            $table->string('std_no',255)->nullable()->comment('เลขที่มาตรฐาน');
            $table->string('std_book',255)->nullable()->comment('เล่ม');
            $table->string('std_year',255)->nullable()->comment('ปี');
            $table->string('std_full',255)->nullable()->comment('ชื่อเต็มเลขมาตรฐาน');
            $table->text('std_title')->nullable()->comment('ชื่อมาตรฐาน');
            $table->text('std_title_en')->nullable()->comment('ชื่อมาตรฐานภาษาอังกฤษ');
            $table->integer('method_id')->nullable()->comment('วิธีการ basic_methods.id');
            $table->integer('format_id')->nullable()->comment('รูปแบบ basic_set_formats.id');
            $table->text('std_abstract')->nullable()->comment('บทคัดย่อ (TH)');
            $table->text('std_abstract_en')->nullable()->comment('บทคัดย่อ (EN)');
            $table->string('isbn_no',255)->nullable()->comment('เลข ISBN');
            $table->bigInteger('created_by')->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
            $table->integer('status_id')->nullable()->comment('สถานะการกำหนดมาตรฐาน 4-อยู่ระหว่างจัดทำมาตรฐานการรับรอง 5-ดำเนินการ และเสนอผู้มีอำนาจลงนาม 6-ลงนามเรียบร้อย 7-เสนอราชกิจจานุเบกษา 8-ประกาศราชกิจจานุเบกษาเรียบร้อย');
            $table->text('std_file')->nullable()->comment('ไฟล์การลงนามการจัดทำมาตรฐาน');
            $table->date('std_sign_date')->nullable()->comment('วันที่ลงนามการจัดทำมาตรฐาน');
            $table->integer('gazette_state')->nullable()->comment('สถานะการประกาศในราชกิจจานุเบกษา');
            $table->string('gazette_book',255)->nullable()->comment('เล่ม');
            $table->string('gazette_section',255)->nullable()->comment('ตอน');
            $table->date('gazette_post_date')->nullable()->comment('วันที่ประกาศในราชกิจจานุเบกษา');
            $table->date('gazette_effective_date')->nullable()->comment('วันที่มีผลใช้งาน/บังคับ');
            $table->text('gazette_file')->nullable()->comment('ไฟล์ประกาศในราชกิจจานุเบกษา');
            $table->integer('publish_state')->nullable()->comment('สถานการณ์เผยแพร่ 1.รอ, 2.เผยแพร่, 3.ยกเลิก');
            $table->date('revoke_date')->nullable()->comment('วันที่ประกาศยกเลิก');
            $table->text('revoke_remark')->nullable()->comment('เหตุผลที่ยกเลิก');
            $table->string('revoke_book',255)->nullable()->comment('ประกาศกระทรวงฯ ฉบับที่');
            $table->text('revoke_file')->nullable()->comment('ไฟล์ประกาศการยกเลิก');
            
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certify_standards');
    }
}
