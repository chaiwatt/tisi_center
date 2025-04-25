<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFactProductToLawCasesFactBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_cases_fact_books', function (Blueprint $table) {
            $table->integer('fact_license_currently')->nullable()->comment('ใบอนุญาต : 1= ปัจจุบันได้รับใบอนุญาตแล้ว, 2=ปัจจุบันยังไม่ได้รับใบอนุญาต');
            $table->integer('fact_product_marking')->nullable()->comment('แสดงเครื่องหมายกับผลิตภัณฑ์ฯ : 1= มี, 2=ไม่มี');
            $table->integer('fact_product_sell')->nullable()->comment('การจำหน่ายผลิตภัณฑ์ฯ :  1= ทั้งหมด, 2=บางส่วน, 3=ไม่มี');
            $table->integer('fact_product_reclaim')->nullable()->comment('ผลิตภัณฑ์ฯที่เรียกคืนได้ :   1= ทั้งหมด, 2=บางส่วน, 3=ไม่มี');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_cases_fact_books', function (Blueprint $table) {
            $table->dropColumn(['fact_license_currently', 'fact_product_marking', 'fact_product_sell', 'fact_product_reclaim']);
        });
    }
}
