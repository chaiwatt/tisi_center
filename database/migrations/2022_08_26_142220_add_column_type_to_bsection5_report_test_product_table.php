<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTypeToBsection5ReportTestProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsection5_report_test_product', function (Blueprint $table) {
            $table->integer('type')->nullable()->comment('ประเภท 1:คำขอทดสอบผลิตภัณฑ์, 2:ยึด-อายัด, 3:ตรวจติดตาม ')->after('remark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsection5_report_test_product', function (Blueprint $table) {
            $table->dropColumn([ 'type']);
        });
    }
}
