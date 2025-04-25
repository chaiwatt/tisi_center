<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeDetailToTb4TisilicenseChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb4_tisilicense_change', function (Blueprint $table) {
            $table->longText('change_detail')->nullable()->after('change_to')->comment('รายละเอียดการเปลี่ยนแปลง');
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
            $table->dropColumn(['change_detail']); 
        });
    }
}
