<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseImpoundProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_impound_products', function (Blueprint $table) {
            $table->increments('id')->comment('รหัสประจำตาราง');
            $table->integer('law_case_impound_id')->nullable()->comment('ID : ตาราง law_case_impound');
            $table->text('detail')->nullable()->comment('รายละเอียดผลิตภัณฑ์');
            $table->integer('amount_impounds')->nullable()->comment('จำนวนที่ยึด');
            $table->string('amount_keep')->nullable()->comment('จำนวนที่อายัด');
            $table->string('unit')->nullable()->comment('หน่วย');
            $table->mediumInteger('price')->nullable()->comment('ราคา/หน่วย');
            $table->mediumInteger('total_price')->nullable()->comment('ราคารวม');
            $table->bigInteger('created_by')->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('law_case_impound_products');
    }
}
