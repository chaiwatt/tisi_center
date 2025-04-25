<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegisterExpertsPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->string('position',255)->nullable()->comment('ตำแหน่ง')->after('department_id');
            $table->text('historycv_text')->nullable()->comment('ระบุความเชี่ยวชาญ')->after('historycv_file');
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
            $table->dropColumn(['position','historycv_text']);
        });
    }
}
