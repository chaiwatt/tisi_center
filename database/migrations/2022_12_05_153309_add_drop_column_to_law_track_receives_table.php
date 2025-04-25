<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDropColumnToLawTrackReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->dropColumn(['accept_date','accept_by']);
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
            $table->date('accept_date')->nullable()->comment('วันที่รับเรื่อง');
            $table->integer('accept_by')->nullable()->comment('ผู้รับเรื่อง');
        });
    }
}
