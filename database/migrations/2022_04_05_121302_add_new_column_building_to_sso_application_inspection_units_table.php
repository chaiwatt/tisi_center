<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnBuildingToSsoApplicationInspectionUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_application_inspection_units', function (Blueprint $table) {
            $table->string('authorized_building',255)->nullable()->comment('ข้อมูลผู้ยื่นขอ อาคาร/หมู่บ้าน')->after('authorized_road');
            $table->string('laboratory_building',255)->nullable()->comment('ห้องปฏิบัติการ อาคาร/หมู่บ้าน')->after('laboratory_road');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_application_inspection_units', function (Blueprint $table) {
            $table->dropColumn(['authorized_building', 'laboratory_building']);
        });
    }
}
