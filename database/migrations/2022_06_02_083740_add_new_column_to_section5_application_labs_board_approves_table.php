<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToSection5ApplicationLabsBoardApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_board_approves', function (Blueprint $table) {
            $table->date('government_gazette_date')->nullable()->comment('วันที่ประกาศราชกิจจา')->after('board_meeting_description');
            $table->date('lab_start_date')->nullable()->comment('วันที่มีผลเป็นหน่วยตรวจสอบ')->after('government_gazette_date');
            $table->date('lab_end_date')->nullable()->comment('วันที่สิ้นสุดเป็นหน่วยตรวจสอบ')->after('lab_start_date');
            $table->text('government_gazette_description')->nullable()->comment('รายละเอียด/หมายเหตุ')->after('lab_end_date');

            $table->integer('government_gazette_created_by')->nullable()->comment('ผู้บันทึกประกาศราชกิจจา');
            $table->integer('government_gazette_updated_by')->nullable()->comment('ผู้แก้ไขประกาศราชกิจจา');
            $table->dateTime('government_gazette_created_at')->nullable()->comment('วันที่สิ้นสุดเป็นหน่วยตรวจสอบ');
            $table->dateTime('government_gazette_updated_at')->nullable()->comment('วันที่สิ้นสุดเป็นหน่วยตรวจสอบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_board_approves', function (Blueprint $table) {
            $table->dropColumn(['government_gazette_date','lab_start_date', 'lab_end_date', 'government_gazette_description', 'government_gazette_created_by', 'government_gazette_updated_by', 'government_gazette_created_at', 'government_gazette_updated_at']);
            
        });
    }
}
