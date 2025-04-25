<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccereditatioNoToSection5ApplicationLabsCerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_cer', function (Blueprint $table) {
            $table->string('accereditatio_no')->nullable()->comment('หมายเลขการรับรอง');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_cer', function (Blueprint $table) {
            $table->dropColumn([ 'accereditatio_no']);
        });
    }
}
