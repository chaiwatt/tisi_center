<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnReadAllToLogsNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs_notifications', function (Blueprint $table) {
            $table->integer('read_all')->nullable()->comment('1 อ่านแล้ว')->after('read');
            $table->integer('type')->nullable()->comment('แจ้งเตือน: 1 => ใบสมัคร, 2 => หมอบหมาย, 3 => อนุมัติ ')->after('users_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs_notifications', function (Blueprint $table) {
            $table->dropColumn(['read_all', 'type']);
        });
    }
}
