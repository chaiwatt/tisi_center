<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdUnitToEsurvInformVolumeLicenseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esurv_inform_volume_license_details', function (Blueprint $table) {
            $table->string('id_unit')->after('volume3')->comment('รหัสหน่วย จากกรมศุล');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esurv_inform_volume_license_details', function (Blueprint $table) {
            $table->dropColumn(['id_unit']);
        });
    }
}
