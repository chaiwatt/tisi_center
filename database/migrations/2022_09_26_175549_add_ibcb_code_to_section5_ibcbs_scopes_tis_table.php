<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIbcbCodeToSection5IbcbsScopesTisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_ibcbs_scopes_tis', function (Blueprint $table) {
            $table->string('ibcb_code', 255)->nullable()->comment('รหัสหน่วยตรวจสอบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_ibcbs_scopes_tis', function (Blueprint $table) {
            $table->dropColumn(['ibcb_code']);
        });
    }
}
