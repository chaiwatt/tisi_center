<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateDiagnosisToLawListenMinistryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->date('date_diagnosis')->after('status_id')->nullable()->comment('วันที่วินิจฉัย');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->dropColumn(['date_diagnosis']);
        });
    }
}
