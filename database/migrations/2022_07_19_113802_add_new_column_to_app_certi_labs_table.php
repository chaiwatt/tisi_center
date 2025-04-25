<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToAppCertiLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {

            $table->text('lab_latitude')->nullable()->comment('พิกัดที่ตั้ง (ละติจูด)');
            $table->text('lab_longitude')->nullable()->comment('พิกัดที่ตั้ง (ลองจิจูด)');

            $table->text('lab_name_en')->nullable()->comment('ชื่อห้องปฏิบัติการ (EN)');
            $table->text('lab_name_short')->nullable()->comment('ชื่อย่อห้องปฏิบัติการ');

            $table->text('lab_address_no_eng')->nullable()->comment('EN: เลขที่');
            $table->text('lab_moo_eng')->nullable()->comment('EN: หมู่ที่');
            $table->text('lab_soi_eng')->nullable()->comment('EN: ตรอก/ซอย');
            $table->text('lab_street_eng')->nullable()->comment('EN: ถนน');
            $table->text('lab_province_eng')->nullable()->comment('EN: จังหวัด');
            $table->text('lab_amphur_eng')->nullable()->comment('EN: เขต/อำเภอ');
            $table->text('lab_district_eng')->nullable()->comment('EN: แขวง/ตำบล');
            $table->text('lab_postcode_eng')->nullable()->comment('EN: รหัสไปรษณีย์:');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_labs', function (Blueprint $table) {
            
            $table->dropColumn([
                                'lab_latitude', 
                                'lab_longitude', 

                                'lab_name_en',
                                'lab_name_short',

                                'lab_address_no_eng',
                                'lab_moo_eng',
                                'lab_soi_eng',
                                'lab_street_eng',
                                'lab_province_eng',
                                'lab_amphur_eng',
                                'lab_district_eng',
                                'lab_postcode_eng'
                            
                            ]);
        });
    }
}
