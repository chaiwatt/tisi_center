<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusCloseToLawListenMinistryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->integer('status_diagnosis')->nullable()->comment('สถานะผลวินิจฉัย : 1 = อนุมัติให้เป็นไปตามมาตรฐาน, 2 = ไม่อนุมัติให้เป็นไปตามมาตรฐาน')->after('date_diagnosis');
            $table->integer('status_close')->nullable()->comment('สถานะปิดงาน : 1 = ปิดงาน')->after('status_id');
            $table->integer('state_listen')->nullable()->comment('สถานะเปิดใช้งานความเห็น : 1 = เปิดใช้งาน')->after('status_close');
            $table->string('book_no')->nullable()->comment('เลขที่หนังสือ')->after('tis_no');
            $table->date('book_date')->nullable()->comment('วันที่หนังสือ')->after('book_no');
            $table->integer('amount')->nullable()->comment('แสดงความเห็นภายใน/วัน')->after('date_end');
            $table->string('sign_id')->nullable()->comment('ผู้ลงนาม')->after('url');
            $table->text('sign_name')->nullable()->comment('ชื่อผู้ลงนาม')->after('sign_id');
            $table->text('sign_position')->nullable()->comment('ตำแหน่ง')->after('sign_name');
            $table->integer('sign_img')->nullable()->comment('แสดงรูปถ่ายลายเซ็น 1=แสดง')->after('sign_position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->dropColumn([
                                'status_diagnosis',
                                'status_close',
                                'state_listen',
                                'book_no',
                                'book_date',
                                'amount',
                                'sign_id',
                                'sign_name',
                                'sign_position',
                                'sign_img'
                            ]);
        });
    }
}
