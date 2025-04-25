<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFormatResultToBsection5TestItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsection5_test_item', function (Blueprint $table) {
            $table->string('format_result')->nullable()
                  ->comment("ประเภทรูปแบบของผลการทดสอบ
                                'integer' => 'เลขจำนวนเต็ม',
                                'integer_range' => 'เลขจำนวนเต็ม (เป็นช่วง)',
                                'decimal' => 'เลขทศนิยม',
                                'decimal_range' => 'เลขทศนิยม (เป็นช่วง)',
                                'select' => 'ตัวเลือก(เลือกได้ค่าเดียว)',
                                'select_multiple' => 'ตัวเลือก (เลือกได้หลายค่า)',
                                'text' => 'ข้อความ',
                                'mix' => 'รวมหลายรูปแบบ'
                            ")
                  ->after('amount_test_list');
            $table->text('format_result_detail')->nullable()->comment('รูปแบบข้อมูลผลการทดสอบ เก็บในรูปแบบ json')->after('format_result');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsection5_test_item', function (Blueprint $table) {
            $table->dropColumn(['format_result', 'format_result_detail']);  
        });
    }
}
