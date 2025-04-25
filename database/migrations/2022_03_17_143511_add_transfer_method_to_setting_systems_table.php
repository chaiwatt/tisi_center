<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransferMethodToSettingSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_systems', function (Blueprint $table) {
            $table->enum('transfer_method', ['redirect', 'form_post'])->default('redirect')->comment('วิธีส่งข้อมูลไปไซต์ปลายทาง redirect=ลิงค์ไปธรรรมดา ให้ปลายทาง get ค่า session จาก cookie, form_post=ส่งค่า session ไปใน form');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_systems', function (Blueprint $table) {
            $table->dropColumn(['transfer_method']);
        });
    }
}
