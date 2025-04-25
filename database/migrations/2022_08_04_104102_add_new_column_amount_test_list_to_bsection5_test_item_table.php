<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnAmountTestListToBsection5TestItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bsection5_test_item', function (Blueprint $table) {
            $table->integer('amount_test_list')->nullable()->after('criteria')->comment('จำนวนครั้งในรายการทดสอบ');
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
            $table->dropColumn(['amount_test_list']);
            
        });
    }
}
