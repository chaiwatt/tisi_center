<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportTestProductDetailResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_report_test_product_details_result', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id')->nullable()->comment('ID : bsection5_report_test_product_details_items');
            $table->integer('test_no')->nullable()->comment('ลำดับรายการทดสอบ');
            $table->text('test_result')->nullable()->comment('ผลการทดสอบ');
            $table->timestamps();

            $table->foreign('item_id')
                    ->references('id')
                    ->on('bsection5_report_test_product_details_items')
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
        Schema::dropIfExists('bsection5_report_test_product_details_result');
    }
}
