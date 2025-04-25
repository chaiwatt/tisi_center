<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabNameToBsection5ReportTestProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsection5_report_test_product', function (Blueprint $table) {
            $table->text('lab_name')->nullable()->comment('ชื่อหน่วยตรวจสอบ')->after('lab_code');
            
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
            $table->dropColumn([ 'lab_name']);
            
        });
    }
}
