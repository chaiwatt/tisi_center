<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTestProductDetailItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_report_test_product_details_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('detail_id')->nullable()->comment('ID : bsection5_report_test_product_details');
            $table->integer('test_product_id')->nullable()->comment('ID : bsection5_report_test_product');
            $table->integer('test_item_id')->nullable()->comment('ID : bsection5_test_item');
            $table->text('test_item_name')->nullable()->comment('รายการทดสอบ');
            $table->text('test_result')->nullable()->comment('สรุปผลการทดสอบของรายการทดสอบ');
            $table->integer('state')->nullable()->comment('สถานะ: 1 ใช้งาน , 2 ไม่ใชช้งาน ');
            $table->timestamps();

            $table->foreign('detail_id')
                    ->references('id')
                    ->on('bsection5_report_test_product_details')
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
        Schema::dropIfExists('bsection5_report_test_product_details_items');
    }
}
