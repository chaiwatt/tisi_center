<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTrackingAuditorsListStatusIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_tracking_auditors_list', function (Blueprint $table) {
            $table->integer('status_id')->nullable()->comment('TB :bcertify_status_auditors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_tracking_auditors_list', function (Blueprint $table) {
            $table->dropColumn(['status_id']);
        });
    }
}
