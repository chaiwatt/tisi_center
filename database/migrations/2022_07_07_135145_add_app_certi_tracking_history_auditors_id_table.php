<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTrackingHistoryAuditorsIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_tracking_history', function (Blueprint $table) {
            $table->integer('auditors_id')->nullable()->after('system')->comment('ID TB : app_certi_tracking_auditors');
            $table->string('table_name',255)->nullable()->after('auditors_id');
            $table->integer('refid')->nullable()->after('table_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_tracking_history', function (Blueprint $table) {
            $table->dropColumn(['auditors_id','table_name','refid']);
        });
    }
}
