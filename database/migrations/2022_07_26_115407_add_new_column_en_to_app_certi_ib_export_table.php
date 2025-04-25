<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnEnToAppCertiIbExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->text('name_unit_en')->nullable()->after('name_unit');

            $table->text('address_en')->nullable()->after('address');
            $table->text('allay_en')->nullable()->after('allay');
            $table->text('village_no_en')->nullable()->after('village_no');
            $table->text('road_en')->nullable()->after('road');
            $table->text('province_name_en')->nullable()->after('province_name');
            $table->text('amphur_name_en')->nullable()->after('amphur_name');
            $table->text('district_name_en')->nullable()->after('district_name');

            $table->text('formula_en')->nullable()->after('formula');
            $table->text('accereditatio_no_en')->nullable()->after('accereditatio_no');

            $table->integer('sign_id')->nullable()->comment('ตารางผู้ลงนาม')->after('attachs');
            $table->string('sign_name')->nullable()->comment('ชื่อผู้ลงนาม')->after('sign_id');
            $table->string('sign_position')->nullable()->comment('ตำแหน่งผู้ลงนาม')->after('sign_name');
            $table->enum('sign_instead', array('0','1'))->default('0')->comment('ปฏิบัติราชการแทนเลขาธิการฯ (0-ไม่ใช่, 1-ใช่)')->after('sign_position');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_ib_export', function (Blueprint $table) {
            $table->dropColumn([ 
                'name_unit_en', 
                'address_en',
                'allay_en',
                'village_no_en',
                'road_en',
                'province_name_en',
                'amphur_name_en',
                'district_name_en',
                'formula_en',
                'accereditatio_no_en',
                'sign_id',
                'sign_name',
                'sign_position',
                'sign_instead'
                
            ]);
        });
    }
}
