<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToTb4TisilicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb4_tisilicense', function (Blueprint $table) {
            $table->integer('tbl_license_Inative')->nullable()->comment('สถานะใบอนุญาต กรณีไม่ใช้งาน:  1 พักใช้, 2 ยกเลิก, 3 เพิกถอน')->after('tbl_licenseStatus');

            $table->string('tbl_licenseStatus',10)->nullable()->comment('สถานะใบอนุญาต: 0 ไม่ใช้งาน, 1 ใช้งาน')->change();
            //NSW
            $table->date('date_pause_start')->nullable()->comment('วันเริ่มพักใบอนุญาต (ใช้สำหรับ NSW)')->change();
            $table->date('date_pause_end')->nullable()->comment('พักถึงงวันที่ (ใช้สำหรับ NSW)')->change();
            $table->string('user_pause',100)->nullable()->comment('userผู้ทำการพัก (ใช้สำหรับ NSW)')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb4_tisilicense', function (Blueprint $table) {
            $table->dropColumn([
                'tbl_license_Inative'
            ]);

            $table->string('tbl_licenseStatus',10)->nullable()->comment('สถานะใบอนุญาต: 0 ไม่ใช้งาน, 1 ใช้งาน')->change();
            //NSW
            $table->date('date_pause_start')->nullable()->comment('วันเริ่มพักใบอนุญาต')->change();
            $table->date('date_pause_end')->nullable()->comment('พักถึงงวันที่')->change();
            $table->string('user_pause',100)->nullable()->comment('userผู้ทำการพัก')->change();
        });
    }
}
