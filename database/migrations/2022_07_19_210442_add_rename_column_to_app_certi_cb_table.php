<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRenameColumnToAppCertiCbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb', function (Blueprint $table) {

            $table->text('name_en_standard')->nullable()->comment('หน่วยรับรอง (EN)');
            $table->text('name_short_standard')->nullable()->comment('ชื่อย่อหน่วยรับรอง');
            
        });

        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->renameColumn('lab_address_no_eng', 'cb_address_no_eng');
            $table->renameColumn('lab_moo_eng', 'cb_moo_eng');
            $table->renameColumn('lab_soi_eng', 'cb_soi_eng');
            $table->renameColumn('lab_street_eng', 'cb_street_eng');
            $table->renameColumn('lab_province_eng', 'cb_province_eng');
            $table->renameColumn('lab_amphur_eng', 'cb_amphur_eng');
            $table->renameColumn('lab_district_eng', 'cb_district_eng');
            $table->renameColumn('lab_postcode_eng', 'cb_postcode_eng');
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
            $table->dropColumn(['name_en_standard', 'name_short_standard']);
        });

        Schema::table('app_certi_cb', function (Blueprint $table) {
            $table->renameColumn('cb_address_no_eng', 'lab_address_no_eng');
            $table->renameColumn('cb_moo_eng', 'lab_moo_eng');
            $table->renameColumn('cb_soi_eng', 'lab_soi_eng');
            $table->renameColumn('cb_street_eng', 'lab_street_eng');
            $table->renameColumn('cb_province_eng', 'lab_province_eng');
            $table->renameColumn('cb_amphur_eng', 'lab_amphur_eng');
            $table->renameColumn('cb_district_eng', 'lab_district_eng');
            $table->renameColumn('cb_postcode_eng', 'lab_postcode_eng');
        });
    }
}
