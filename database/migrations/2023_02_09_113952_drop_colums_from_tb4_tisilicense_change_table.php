<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumsFromTb4TisilicenseChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb4_tisilicense_change', function (Blueprint $table) {
            $table->dropColumn(['change_field', 'change_from', 'change_to']); 
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
            $table->text('change_field')->nullable()->after('change_type')->comment('ฟิดล์ที่เปลี่ยน');
            $table->text('change_from')->nullable()->after('change_field')->comment('เปลี่ยนจาก');
            $table->text('change_to')->nullable()->after('change_from')->comment('เปลี่ยนเป็น');
        });
    }
}
