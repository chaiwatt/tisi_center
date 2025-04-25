<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarkToSection5ApplicationIbcbScopesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_ibcb_scopes_details', function (Blueprint $table) {
            $table->text('remark')->nullable()->comment('หมายเหตุผลตรวจประเมิน');
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
            $table->dropColumn(['remark']);
        });
    }
}
