<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_cases', function (Blueprint $table) {
            $table->increments('id')->comment('รหัสประจำตาราง');
            $table->string('ref_no', 100)->comment('เลขที่อ้างอิง');
            $table->string('case_number', 100)->nullable()->comment('เลขคดี');
            $table->integer('assign_by')->nullable()->comment('ผู้รับผิดชอบ (ผอ.)');
            $table->text('lawyer_by')->nullable()->comment('นิติกรเจ้าของคดี (json)');
            $table->integer('owner_depart_type')->nullable()->comment('เจ้าของคดี : ประเภทหน่วยงาน 1=ภายใน (สมอ.) 2=ภายนอก');
            $table->text('owner_department_name')->nullable()->comment('เจ้าของคดี : ชื่อหน่วยงาน/กอง/กลุ่ม - เก็บ text');
            $table->integer('owner_sub_department_id')->nullable()->comment('เจ้าของคดี : กอง/กลุ่ม (กรณีภายใน) ตารางอ้างอิง sub_department');
            $table->integer('owner_basic_department_id')->nullable()->comment('เจ้าของคดี : ชื่อหน่วยงาน (กรณีภายนอก) ตารางอ้างอิง law_basic_department');
            $table->integer('owner_case_by')->nullable()->comment('เจ้าของคดี : อ้างอิงตาราง user_register');
            $table->string('owner_name')->nullable()->comment('เจ้าของคดี : ชื่อ-สกุลเจ้าของคดี');
            $table->string('owner_email')->nullable()->comment('เจ้าของคดี : อีเมล');
            $table->string('owner_taxid',30)->nullable()->comment('เจ้าของคดี : เลขนิติบุคคล/เลขบัตรประชาชน');
            $table->string('owner_tel',70)->nullable()->comment('เจ้าของคดี : เบอร์โทรศัพท์');
            $table->string('owner_phone',70)->nullable()->comment('เจ้าของคดี : เบอร์มือถือ');
            $table->string('owner_contact_options')->nullable()->comment('เจ้าของคดี : ข้อมูลเดียวกับเจ้าของคดี');
            $table->string('owner_contact_name')->nullable()->comment('เจ้าของคดี : ชื่อผู้ประสานงาน');
            $table->string('owner_contact_phone')->nullable()->comment('เจ้าของคดี : เบอร์มือถือผู้ประสานงาน');
            $table->string('owner_contact_email')->nullable()->comment('เจ้าของคดี : อีเมลผู้ประสานงาน');
            $table->integer('offend_license_type')->nullable()->comment('1=มีใบอนุญาต 2=ไม่มีเลขใบอนุญาต');
            $table->integer('offend_tb4_tisilicense_id')->nullable()->comment('ความผิด : ใบอนุญาต อ้างอิงตาราง  tb4_tisilicense');
            $table->string('offend_license_number')->nullable()->comment('ความผิด : เลขที่ใบอนุญาต');
            $table->enum('offend_license_notify',['0','1'])->nullable()->comment('ความผิด : มีหนังสือแจ้งเตือนพักใช้ใบอนุญาต 1=มีหนังสือแจ้งเตือน เงื่อนไขบังคับแนบไฟล์แจ้งเตือนพักใช้');
            $table->integer('offend_sso_users_id')->nullable()->comment('ความผิด : ผู้ประกอบการ/ผู้กระทำความผิด id อ้างอิงตาราง sso_users');
            $table->string('offend_name')->nullable()->comment('ความผิด : ผู้ประกอบการ/ผู้กระทำความผิด ชื่อ-สกุล');
            $table->string('offend_taxid')->nullable()->comment('ความผิด : Tax ID');
            $table->text('offend_address')->nullable()->comment('ที่ตั้งสำนักงานใหญ่');
            $table->string('offend_tel',50)->nullable()->comment('เบอร์โทรศัพท์');
            $table->string('offend_email',100)->nullable()->comment('อีเมล');
            $table->string('offend_power')->nullable()->comment('กรรมการบริษัท (json)');
            $table->integer('tis_id')->nullable()->comment('มอก. id อ้างอิงตาราง tb3_tis');
            $table->string('tb3_tisno')->nullable()->comment('มอก. number');
            $table->string('offend_contact_name')->nullable()->comment('ชื่อสกุลผู้ประสานงาน');
            $table->string('offend_contact_tel',50)->nullable()->comment('เบอร์โทร');
            $table->string('offend_contact_email',100)->nullable()->comment('อีเมล');
            $table->integer('law_basic_arrest_id')->nullable()->comment('การจับกุม');
            $table->integer('law_basic_offend_type_id')->nullable()->comment('สาเหตุที่พบการกระทำความผิด');
            $table->string('offend_ref_tb',255)->nullable()->comment('ตารางการตรวจพบความผิด');
            $table->integer('offend_ref_id')->nullable()->comment('เลขที่อ้างอิงการตรวจพบความผิด');
            $table->text('law_basic_section_id')->comment('ฝ่าฝืนตามมาตรา (่json)');
            $table->text('config_evidence')->nullable()->comment('ตั้งค่าไฟล์แนบ');
            $table->integer('status')->comment('สถานะ 99 = ยกเลิก 0 = ฉบับร่าง 1 = แจ้งงานคดีสำเร็จ 2 = อยู่ระหว่างตรวจสอบข้อมูล 3 = ขอข้อมูลเพิ่มเติม (ตีกลับ) 4 = ข้อมูลครบถ้วนอยู่ระหว่างพิจารณา 5 = พบการกระทำความผิด 6 = ไม่พบการกระทำความผิด 7 = ส่งเรื่องดำเนินคดี 8 = แจ้งการกระทำความผิด 9 = อยู่ระหว่างเปรียบเทียบปรับ 10 = เปรียบปรับแล้ว ');
            $table->text('notify_email_type')->nullable()->comment('ท่านต้องการรับอีเมลแจ้งเตือน (json) 1 = เจ้าของคดี 2 = ประสานงาน (เจ้าของคดี) 3 = ผู้ประสานงาน (ผู้กระทำความผิด)');
            $table->text('notify_email_list')->nullable()->comment('รายชื่ออีเมลที่รับแจ้งเตือน (json)');
            $table->integer('cancel_by')->nullable()->comment('ผู้ยกเลิก');
            $table->timestamp('cancel_at')->nullable()->comment('วันที่ยกเลิก');
            $table->text('cancel_remark')->nullable()->comment('หมายเหตุ');
            $table->integer('accept_by')->nullable()->comment('ผู้รับแจ้งเตือน');
            $table->timestamp('accept_at')->nullable()->comment('วันที่รับแจ้งเตือน');
            $table->integer('status_close')->nullable()->comment('1 = ปิดงานคดี');
            $table->timestamp('close_date')->nullable()->comment('วันที่ปิด');
            $table->text('close_remark')->nullable()->comment('หมายเหตุ');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('law_cases');
    }
}
