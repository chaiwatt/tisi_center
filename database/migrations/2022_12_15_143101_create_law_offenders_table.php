<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawOffendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_offenders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('sso_users_id')->nullable()->comment('ID ตาราง sso_users');
            $table->string('type_id')->nullable()->comment('ประเภท: นิติบุคคล, บุคคลธรรมดา, คณะบุคคล, ส่วนราชการ, อื่นๆ');
            $table->text('name')->nullable()->comment('ชื่อผู้กระทำความผิด');
            $table->string('taxid', 255)->nullable()->comment('เลขนิติบุคคล');

            $table->string('address_no')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: ที่อยู่');
            $table->string('moo')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: หมู่');
            $table->string('soi')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: ซอย');
            $table->string('building',255)->nullable()->comment('ที่ตั้งสำนักงานใหญ่: อาคาร/หมู่บ้าน');
            $table->string('street')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: ซอยถนน');
            $table->integer('subdistrict_id')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: ตำบล/แขวง');
            $table->integer('district_id')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: อำเภอ/เขต');
            $table->integer('province_id')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: จังหวัด');
            $table->string('zipcode')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: รหัสไปรษณีย์');
            $table->string('tel')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: เบอร์โทรศัพท์');
            $table->string('fax')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: เบอร์แฟกซ์');
            $table->string('email')->nullable()->comment('ที่ตั้งสำนักงานใหญ่: อีเมล');

            $table->text('power')->nullable()->comment('กรรมการบริษัท json');

            $table->string('contact_name')->nullable()->comment('ผู้ประสานงาน: ชื่อผู้ประสานงาน');
            $table->string('contact_position')->nullable()->comment('ผู้ประสานงาน: ตำแหน่งผู้ประสานงาน');
            $table->string('contact_mobile')->nullable()->comment('ผู้ประสานงาน: เบอร์มือถือ');
            $table->string('contact_phone')->nullable()->comment('ผู้ประสานงาน: เบอร์โทรศัพท์');
            $table->string('contact_fax')->nullable()->comment('ผู้ประสานงาน: เบอร์แฟกซ์');
            $table->string('contact_email')->nullable()->comment('ผู้ประสานงาน: อีเมล');

            $table->date('date_offender')->nullable()->comment('วันที่พบการกระทำผิดครั้งแรก');
            $table->integer('import_data')->nullable()->comment('นำเข้า');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = ใช้งาน, 2 = ไม่ใช้งาน');
            $table->text('remark')->nullable()->comment('หมายเหตุ');

            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
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
        Schema::dropIfExists('law_offenders');
    }
}
