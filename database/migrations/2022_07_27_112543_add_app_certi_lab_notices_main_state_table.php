<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiLabNoticesMainStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_lab_notices', function (Blueprint $table) {
            $table->integer('main_state')->nullable()->comment('การตรวจประเมิน 1.เอกสารครบแล้ว 2.ปิดผลการตรวจประเมิน');
            $table->integer('degree')->nullable();
        });
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_lab_notices', function (Blueprint $table) {
            $table->dropColumn(['main_state','degree']);
        });
    }
} 
