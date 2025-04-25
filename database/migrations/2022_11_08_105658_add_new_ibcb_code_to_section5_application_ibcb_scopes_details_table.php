<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewIbcbCodeToSection5ApplicationIbcbScopesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_ibcb_scopes_details', function (Blueprint $table) {
            $table->integer('ibcb_id')->nullable()->comment('ID : ตาราง section5_ibcbs');
            $table->string('ibcb_code', 255)->nullable()->comment('หน่วยตรวจสอบ: รหัสหน่วยตรวจสอบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_ibcb_scopes_details', function (Blueprint $table) {
            $table->dropColumn(['ibcb_id', 'ibcb_code']);
        });
    }
}
