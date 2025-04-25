<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLawNotifyNotifyTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_notify', function (Blueprint $table) {
            $table->text('notify_type')->nullable()->comment('แจ้งเตือนไปยัง (json) 1.เจ้าหน้าที่, 2.ผู้ประสานงาน (เจ้าของคดี), 2.ผู้ประสานงาน (กระทำความคิด), 4.ผู้มอบหมายงาน (ผก.), 5.ผู้บริการ (ผอ.)')->change();
            $table->text('email')->nullable()->comment('รายชื่ออีเมลส่งถึง (json)')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_notify', function (Blueprint $table) {
            $table->text('notify_type')->nullable()->comment('1.เจ้าหน้าที่, 2.ผู้ประสานงาน (เจ้าของคดี), 2.ผู้ประสานงาน (กระทำความคิด), 4.ผู้มอบหมายงาน (ผก.), 5.ผู้บริการ (ผอ.)')->change();
            $table->string('email',255)->nullable()->comment('รายชื่ออีเมลส่งถึง (json)')->change();
        });
    }
}
