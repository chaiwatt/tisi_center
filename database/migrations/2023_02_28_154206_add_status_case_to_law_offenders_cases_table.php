<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusCaseToLawOffendersCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_offenders_cases', function (Blueprint $table) {
            $table->enum('prosecute', ['0', '1'])->default('0')->comment('ดำเนินคดี 0.ไม่ดำเนินคดี, 1.ดำเนินคดี');
            $table->integer('episode_offenders')->nullable()->comment('ครั้งที่กระทำความผิด');
            $table->decimal('total_price',30,2)->nullable()->comment('มูลค่าของกลาง');
            $table->decimal('total_compare',30,2)->nullable()->comment('ค่าเปรียบเทียบปรับ');
            $table->date('payment_date')->nullable()->comment('วันที่ชำระเงินค่าปรับ');

            $table->text('power')->nullable()->comment('อำนาจ (เสนอ ลมอ./คกก.)');
            $table->date('power_present_date')->nullable()->comment('วันที่เสนอ');

            $table->date('approve_date')->nullable()->comment('วันที่อนุมัติ');
            $table->date('assign_date')->nullable()->comment('วันที่ได้รับมอบหมาย');

            //กมอ.
            $table->text('tisi_present')->nullable()->comment('เสนอลงนามคำสั่ง กมอ.');
            $table->text('tisi_dictation_no')->nullable()->comment('คำสั่งกมอ. ที่');
            $table->date('tisi_dictation_date')->nullable()->comment('วันที่คำสั่ง กมอ.ทำให้สิ้นสภาพ');
            //แจ้งคำสั่ง
            $table->text('tisi_dictation_cppd')->nullable()->comment('แจ้งคำสั่ง กมอ.(ปคบ.)');
            $table->text('tisi_dictation_company')->nullable()->comment('แจ้งคำสั่ง กมอ.(บริษัท)');
            $table->text('tisi_dictation_committee')->nullable()->comment('แจ้งคำสั่ง กมอ. คืนเรื่องเดิม (กต.)');

            //ปคบ cppd
            $table->text('cppd_result')->nullable()->comment('แจ้งผล การเปรียบเทียบปรับ(ปคบ.)');

            //ลมอ.
            $table->text('result_summary')->nullable()->comment('สรุปเรื่องให้ลมอ. ทราบ');

            $table->date('destroy_date')->nullable()->comment('วันที่ทำลาย/ ส่งคืน');

            $table->integer('depart_type')->nullable()->comment('เจ้าของคดี : ประเภทหน่วยงาน 1 = ภายใน (สมอ.) 2 = ภายนอก');
            $table->integer('sub_department_id')->nullable()->comment('เจ้าของคดี : กอง/กลุ่ม (กรณีภายใน) ตารางอ้างอิง sub_department');
            $table->integer('basic_department_id')->nullable()->comment('เจ้าของคดี : ชื่อหน่วยงาน (กรณีภายนอก) ตารางอ้างอิง law_basic_department');
            $table->string('department_name')->nullable()->comment('ชื่อหน่วยงาน/กอง/กลุ่ม');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_offenders_cases', function (Blueprint $table) {
            $table->dropColumn([
                'prosecute',
                'episode_offenders',
                'total_price',
                'total_compare',
                'payment_date',
                'power',
                'power_present_date',
                'approve_date',
                'assign_date',
                'tisi_present_date',
                'tisi_dictation_no',
                'tisi_dictation_date',
                'tisi_dictation_cppd',
                'tisi_dictation_company',
                'tisi_dictation_committee',
                'cppd_result',
                'result_summary',
                'destroy_date',
                'depart_type',
                'sub_department_id',
                'basic_department_id',
                'department_name'
            ]);
        });
    }
}
