<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestSummaryToBsection5TestItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsection5_test_item', function (Blueprint $table) {
            $table->enum('test_summary', ['1', '2'])->default(1)->after('input_result')->comment('สรุปผลทดสอบ 1=มี, 2=ไม่มี');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsection5_test_item', function (Blueprint $table) {
            $table->dropColumn('test_summary');
        });
    }
}
