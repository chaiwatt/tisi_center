<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeDisplayToTb4TisilicenseChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb4_tisilicense_change', function (Blueprint $table) {
            $table->longText('change_display')->nullable()->after('change_detail')->comment('รายละเอียดที่จะเอาไปแสดง');
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
            $table->dropColumn(['change_display']); 
        });
    }
}
