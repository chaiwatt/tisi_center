<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIbcbEndDateToSection5IbcbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_ibcbs', function (Blueprint $table) {
            $table->date('ibcb_end_date')->nullable()->comment('วันที่สิ้นสุด IBCB')->after('ibcb_start_date');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_ibcbs', function (Blueprint $table) {
            $table->dropColumn(['ibcb_end_date']);
        });
    }
}
