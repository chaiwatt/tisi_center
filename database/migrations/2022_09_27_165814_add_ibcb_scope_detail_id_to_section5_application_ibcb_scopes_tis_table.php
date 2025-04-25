<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIbcbScopeDetailIdToSection5ApplicationIbcbScopesTisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_ibcb_scopes_tis', function (Blueprint $table) {
            $table->unsignedInteger('ibcb_scope_detail_id')->nullable()->after('ibcb_scope_id')->comment('id ตาราง section5_application_ibcb_scopes_details');
            $table->foreign('ibcb_scope_detail_id', 'ibcb_scope_detail_id_fk_scope_detail')
                  ->references('id')
                  ->on('section5_application_ibcb_scopes_details')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_ibcb_scopes_tis', function (Blueprint $table) {
            $table->dropForeign('ibcb_scope_detail_id_fk_scope_detail');
            $table->dropColumn(['ibcb_scope_detail_id']);
        });
    }
}
