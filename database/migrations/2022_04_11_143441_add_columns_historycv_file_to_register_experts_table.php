<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsHistorycvFileToRegisterExpertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->text('historycv_file')->nullable()->comment('ไฟล์ประวัติความเชี่ยวชาญ (CV)')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->dropColumn(['historycv_file']);
        });
    }
}
