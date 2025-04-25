<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGazetteBookCertifyStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_standards', function (Blueprint $table) {
            $table->string('gazette_no',255)->nullable()->comment('เล่ม')->after('std_book');
            $table->string('std_page',255)->nullable()->comment('จำนวนหน้า')->after('std_title_en');
            $table->date('isbn_issue_at')->nullable()->comment('วันที่รับคำขอ')->after('isbn_no');
            $table->text('isbn_file')->nullable()->comment('หลักฐานไฟล์แนบ')->after('isbn_issue_at');
            $table->integer('isbn_by')->nullable()->comment('ผู้บันทึก')->after('isbn_file');
            $table->date('isbn_at')->nullable()->comment('วันที่บันทึก')->after('isbn_by');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_standards', function (Blueprint $table) {
            $table->dropColumn(['std_page', 'gazette_no','isbn_issue_at','isbn_file','isbn_by','isbn_at']);
        });
    }
}
