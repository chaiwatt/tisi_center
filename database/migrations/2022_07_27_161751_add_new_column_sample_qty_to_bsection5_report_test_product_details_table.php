<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnSampleQtyToBsection5ReportTestProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsection5_report_test_product_details', function (Blueprint $table) {
            $table->text('sample_qty')->nullable()->comment('จำนวนตัวอย่าง')->after('sample_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsection5_report_test_product_details', function (Blueprint $table) {
            $table->dropColumn([ 'sample_qty']);
        });
    }
}
