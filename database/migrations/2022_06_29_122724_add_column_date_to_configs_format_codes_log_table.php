<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDateToConfigsFormatCodesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_format_codes_log', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable()->comment('วันที่เริ่มต้น');
            $table->dateTime('end_date')->nullable()->comment('วันที่สิ้นสุด');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 0 = Not Active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configs_format_codes_log', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'state']);
        });
    }
}
