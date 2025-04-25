<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeDateToTb4TisilicenseChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb4_tisilicense_change', function (Blueprint $table) {
            $table->date('change_date')->nullable()->after('change_display')->comment('วันที่เปลี่ยนแปลง');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb4_tisilicense_change', function (Blueprint $table) {
            $table->dropColumn(['change_date']); 
        });
    }
}
