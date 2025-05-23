<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSytemsToConfigsFormatCodesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configs_format_codes_log', function (Blueprint $table) {
            $table->string('system')->nullable()->comment('ระบบงาน');
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
            $table->dropColumn(['system']);
        });
    }
}
