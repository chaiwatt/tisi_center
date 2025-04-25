<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTb3TisnoInEsurvInformVolumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esurv_inform_volumes', function (Blueprint $table) {
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
        Schema::table('esurv_inform_volumes', function (Blueprint $table) {
            $table->string('tb3_Tisno', 25)->nullable()->comment('เลขที่ มอก.ตาราง tb3_tis')->change();
        });
    }
}
