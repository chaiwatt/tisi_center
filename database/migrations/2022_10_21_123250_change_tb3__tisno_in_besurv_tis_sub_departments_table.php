<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTb3TisnoInBesurvTisSubDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('besurv_tis_sub_departments', function (Blueprint $table) {
            $table->string('tb3_Tisno', 60)->nullable()->comment('เลขที่ มอก.ตาราง tb3_tis')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('besurv_tis_sub_departments', function (Blueprint $table) {
            $table->string('tb3_Tisno', 25)->nullable()->comment('เลขที่ มอก.ตาราง tb3_tis')->change();
        });
    }
}
