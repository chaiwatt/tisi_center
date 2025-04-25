<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateToSection5LabsScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_labs_scopes', function (Blueprint $table) {
            $table->date('start_date')->nullable()->comment('วันที่เริ่มต้น');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุด');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_labs_scopes', function (Blueprint $table) {
            $table->dropColumn(['start_date','end_date']);
        });
    }
}
