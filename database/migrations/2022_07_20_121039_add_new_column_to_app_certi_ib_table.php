<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToAppCertiIbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_ib', function (Blueprint $table) {
            $table->string('hq_address')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ที่อยู่สำนักงานใหญ่');
            $table->string('hq_moo')->nullable()->comment('ข้อมูลสำนักงานใหญ่: หมู่');
            $table->string('hq_soi')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ซอย');
            $table->string('hq_road')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ซอยถนน');
            $table->integer('hq_subdistrict_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: ตำบล/แขวง');
            $table->integer('hq_district_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: อำเภอ/เขต');
            $table->integer('hq_province_id')->nullable()->comment('ข้อมูลสำนักงานใหญ่: จังหวัด');
            $table->string('hq_zipcode')->nullable()->comment('ข้อมูลสำนักงานใหญ่: รหัสไปรษณีย์');

            $table->date('hq_date_registered')->nullable()->comment('ข้อมูลสำนักงานใหญ่: จดทะเบียนเป็นนิติบุคคลเมื่อวันที่');
            $table->string('hq_telephone')->nullable()->comment('ข้อมูลสำนักงานใหญ่: โทรศัพท์');
            $table->string('hq_fax')->nullable()->comment('ข้อมูลสำนักงานใหญ่: โทรสาร');

            $table->text('ib_latitude')->nullable()->comment('พิกัดที่ตั้ง (ละติจูด)');
            $table->text('ib_longitude')->nullable()->comment('พิกัดที่ตั้ง (ลองจิจูด)');

            $table->text('name_en_unit')->nullable()->comment('หน่วยตรวจสอ (EN)');
            $table->text('name_short_unit')->nullable()->comment('ชื่อย่อหน่วยตรวจสอบ');

            $table->text('ib_address_no_eng')->nullable()->comment('EN: เลขที่');
            $table->text('ib_moo_eng')->nullable()->comment('EN: หมู่ที่');
            $table->text('ib_soi_eng')->nullable()->comment('EN: ตรอก/ซอย');
            $table->text('ib_street_eng')->nullable()->comment('EN: ถนน');
            $table->text('ib_province_eng')->nullable()->comment('EN: จังหวัด');
            $table->text('ib_amphur_eng')->nullable()->comment('EN: เขต/อำเภอ');
            $table->text('ib_district_eng')->nullable()->comment('EN: แขวง/ตำบล');
            $table->text('ib_postcode_eng')->nullable()->comment('EN: รหัสไปรษณีย์:');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_ib', function (Blueprint $table) {
            $table->dropColumn([
                'hq_address',
                'hq_moo',
                'hq_soi',
                'hq_road',
                'hq_subdistrict_id',
                'hq_district_id',
                'hq_province_id',
                'hq_zipcode',
                'hq_date_registered',
                'hq_telephone',
                'hq_fax',

                'ib_latitude', 
                'ib_longitude', 

                'name_en_unit',
                'name_short_unit',

                'ib_address_no_eng',
                'ib_moo_eng',
                'ib_soi_eng',
                'ib_street_eng',
                'ib_province_eng',
                'ib_amphur_eng',
                'ib_district_eng',
                'ib_postcode_eng'

            ]);
        });
    }
}
