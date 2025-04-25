<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdUnitToEsurvApplicant21terProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esurv_applicant_21ter_product_details', function (Blueprint $table) {
            $table->string('id_unit')->after('quantity')->comment('รหัสหน่วย จากกรมศุล');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('esurv_applicant_21ter_product_details', function (Blueprint $table) {
            $table->dropColumn(['id_unit']);
        });
    }
}
