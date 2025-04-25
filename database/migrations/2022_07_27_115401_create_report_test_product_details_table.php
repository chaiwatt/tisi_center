<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTestProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_report_test_product_details', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('test_product_id')->nullable();
            $table->text('sample_bill_no')->nullable()->comment('เลขที่ใบนำส่งตัวอย่าง');
            $table->text('product_detail')->nullable()->comment('รายละเอียยดผลิตภัณฑ์');
            $table->text('sample_no')->nullable()->comment('หมายเลขตัวอย่าง');

            $table->timestamps();

            $table->foreign('test_product_id')
                    ->references('id')
                    ->on('bsection5_report_test_product')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_test_product_details');
    }
}
