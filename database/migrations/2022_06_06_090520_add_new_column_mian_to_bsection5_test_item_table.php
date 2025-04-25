<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnMianToBsection5TestItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsection5_test_item', function (Blueprint $table) {
            $table->integer('main_topic_id')->nullable()->comment('id bsection5_test_item : หัวข้อหลัก ')->after('state');
            $table->string('level')->nullable()->comment('ระดับชั้น')->after('main_topic_id');
        });
    }
 
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsection5_test_item', function (Blueprint $table) {
            $table->dropColumn(['main_topic_id','level']);
        });
    }
}
