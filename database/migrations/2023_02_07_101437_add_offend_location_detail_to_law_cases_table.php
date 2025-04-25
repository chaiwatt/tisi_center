<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOffendLocationDetailToLawCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->text('offend_location_detail')->nullable()->comment('รายละเอียดเกี่ยวกับสถานที่ที่ตรวจพบการกระทำความผิด')->after('offend_power');
            $table->text('offend_product_detail')->nullable()->comment('รายละเอียดเกี่ยวกับผลิตภัณฑ์ที่ไม่เป็นไปตามมาตรฐาน')->after('offend_location_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->dropColumn(['offend_location_detail','offend_product_detail']);
        });
    }
}
