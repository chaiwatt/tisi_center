<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisiToSection5ApplicationLabsBoardApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_board_approves', function (Blueprint $table) {
            $table->integer('tisi_board_meeting_result')->nullable()->comment('มติกมอ. 1 = ผ่าน 2 = ไม่ผ่าน');
            $table->date('tisi_board_meeting_date')->nullable()->comment('วันที่ประชุมกมอ.');
            $table->text('tisi_board_meeting_description')->nullable()->comment('รายละเอียด/หมายเหตุกมอ.');
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
            $table->dropColumn(['tisi_board_meeting_result', 'tisi_board_meeting_date', 'tisi_board_meeting_description']);
        });
    }
}
