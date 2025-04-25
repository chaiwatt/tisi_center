<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiCbExportSetFormatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->string('set_format',255)->nullable()->comment('กำหนดรูปแบบใบรับรอง');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->dropColumn(['set_format']);
        });
    }
}
