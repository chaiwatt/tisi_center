<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTisiTypeToLawListenMinistryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->integer('tis_type')->nullable()->default(2)->comment('การเลือก มอก. มี 2 ประเภท  1. เลือกจากฐาน 2. คีย์เอง');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->dropColumn('tis_type');
        });
    }
}
