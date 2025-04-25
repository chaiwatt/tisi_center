<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCasesCompareTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases', function (Blueprint $table) {
            $table->integer('compare_type')->default('0')->comment('บันทึกผลยินยอมเปรียบเทียบปรับ : ส่งหลักฐานกลับมายัง สมอ.');
            $table->text('compare_remark')->nullable()->comment('บันทึกผลยินยอมเปรียบเทียบปรับ : หมายเหตุ');
            $table->integer('compare_by')->nullable()->comment('บันทึกผลยินยอมเปรียบเทียบปรับ ID : user_register');
            $table->dateTime('compare_at')->nullable()->comment('บันทึกผลยินยอมเปรียบเทียบปรับ  : วันที่บันทึก');
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
            //
        });
    }
}
