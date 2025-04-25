<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuditResultToSection5ApplicationIbcbScopesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_ibcb_scopes_details', function (Blueprint $table) {
            $table->tinyInteger('audit_result')->nullable()->comment('ผลตรวจประเมิน 1 = ผ่าน, 2 = ไม่ผ่าน');
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
            $table->dropColumn(['audit_result']);
        });
    }
}
