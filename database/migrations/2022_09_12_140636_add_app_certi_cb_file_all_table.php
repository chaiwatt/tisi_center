<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiCbFileAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb_file_all', function (Blueprint $table) {
            $table->dateTime('issue_date')->nullable()->comment('ออกให้ ณ วันที่');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb_file_all', function (Blueprint $table) {
            $table->dropColumn(['issue_date']);
        });
    }
}
