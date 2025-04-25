<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCasesProductResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_cases_product_results', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('law_cases_id')->nullable()->comment('ID ตาราง law_cases');
            $table->integer('law_case_impound_id')->nullable()->comment('ID ตาราง law_case_impounds');
            $table->integer('law_case_impound_products_id')->nullable()->comment('ID ตาราง law_case_impound_products');

            $table->integer('status_product')->nullable()->comment('สถานะ : 1 รอดำเนินการ, 2 อยู่รหว่างดำเนินการ, 3 ดำเนินการเสร็จสิ้น  ');
            $table->integer('result_process_product_id')->nullable()->comment('ID ตาราง law_basic_process_product');

            $table->text('result_description')->comment('โดยวิธีการ');
            $table->date('result_start_date')->nullable()->comment('วันที่มีคำสั่ง');
            $table->date('result_end_date')->nullable()->comment('วันที่สิ้นสุดคำสั่ง');
            $table->text('result_amount')->comment('ภายในจำนวนวัน');
            $table->text('result_remark')->comment('หมายเหตุ');

            $table->bigInteger('result_by')->nullable()->comment('ผู้บันทึก');
            $table->dateTime('result_at')->nullable()->comment('วันที่บันทึก');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('law_cases_product_results');
    }
}
