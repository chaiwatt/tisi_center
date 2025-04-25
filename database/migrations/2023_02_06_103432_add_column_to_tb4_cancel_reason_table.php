<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToTb4CancelReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb4_cancel_reason', function (Blueprint $table) {
            $table->integer('type_date')->nullable()->comment('1 = ยกเลิก,2 =เพิกถอน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb4_cancel_reason', function (Blueprint $table) {
            $table->dropColumn([
                'type_date'
            ]);
        });
    }
}
