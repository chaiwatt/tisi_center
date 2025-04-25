<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppNameToSettingSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_systems', function (Blueprint $table) {
            $table->string('app_name', 255)->nullable()->comment('ชื่อแอพ ที่แอพอื่นๆ จะใช้ตรวจสอบว่าผู้ใช้งานได้รับอนุญาตให้ใช้งานหรือไม่');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_systems', function (Blueprint $table) {
            $table->dropColumn(['app_name']);
        });
    }
}
