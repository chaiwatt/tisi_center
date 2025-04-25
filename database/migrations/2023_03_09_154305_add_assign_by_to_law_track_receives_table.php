<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssignByToLawTrackReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->integer('assign_by')->nullable()->comment('ผู้รับผิดชอบ')->after('receive_time');
            $table->date('assign_at')->nullable()->comment('วันที่มอบหมายผู้รับผิดชอบ')->after('assign_by');
            $table->integer('lawyer_by')->nullable()->comment('ผู้ได้รับมอบหมาย')->after('assign_at');
            $table->date('lawyer_at')->nullable()->comment('วันที่ได้รับมอบหมาย')->after('lawyer_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->dropColumn(['assign_by','assign_at','lawyer_at','lawyer_by']);
        });
    }
}
