<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToCertificateExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_exports', function (Blueprint $table) {
            
            $table->text('lab_name_en')->nullable()->after('lab_name');
            $table->text('address_no_en')->nullable()->after('address_no');
            $table->text('address_moo_en')->nullable()->after('address_moo');
            $table->text('address_soi_en')->nullable()->after('address_soi');
            $table->text('address_road_en')->nullable()->after('address_road');
            $table->text('address_province_en')->nullable()->after('address_province');
            $table->text('address_district_en')->nullable()->after('address_district');
            $table->text('address_subdistrict_en')->nullable()->after('address_subdistrict');
            $table->text('formula_en')->nullable()->after('formula');
            $table->text('accereditatio_no_en')->nullable()->after('accereditatio_no');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_exports', function (Blueprint $table) {
            $table->dropColumn([ 
                                    'lab_name_en', 
                                    'address_no_en',
                                    'address_moo_en',
                                    'address_soi_en',
                                    'address_road_en',
                                    'address_province_en',
                                    'address_district_en',
                                    'address_subdistrict_en',
                                    'formula_en',
                                    'accereditatio_no_en'
                                ]);
            
        });
    }
}
