<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToLawCaseBookOffendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_book_offend', function (Blueprint $table) {

            $table->text('book_title')->nullable()->comment('เรื่อง');
            $table->text('book_date')->nullable()->comment('วันที่ในหนังสือ');
            $table->text('book_to')->nullable()->comment('เรียน');
            $table->text('book_enclosure')->nullable()->comment('สิ่งที่ส่งมาด้วย');
            $table->text('offend_act')->nullable()->comment('ความผิดตาม พรบ. 2511');
            $table->text('offend_report')->nullable()->comment('แจ้งข้อกล่าวหา');
            $table->integer('lawyer_id')->nullable()->comment('นิติกร');

            $table->unsignedInteger('law_cases_id')->nullable()->comment('ID ตาราง law_cases');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_book_offend', function (Blueprint $table) {
            $table->dropColumn([
                'book_title',
                'book_date',
                'book_to',
                'book_enclosure',

                'offend_act',
                'offend_report',
                'lawyer_id',
                'law_cases_id'

            ]);
        });
    }
}
