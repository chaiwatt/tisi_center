<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDropColumnToAppCertiCbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->dropColumn(['lab_name_short', 'lab_name_en']);
        });

        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->renameColumn('lab_latitude', 'cb_latitude');
            $table->renameColumn('lab_longitude', 'cb_longitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->text('lab_name_en')->nullable()->comment('ชื่อห้องปฏิบัติการ (EN)');
            $table->text('lab_name_short')->nullable()->comment('ชื่อย่อห้องปฏิบัติการ');
        });

        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->renameColumn('cb_latitude', 'lab_latitude');
            $table->renameColumn('cb_longitude', 'lab_longitude');
        });
    }
}
