<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInitialSection5IbcbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_ibcbs', function (Blueprint $table) {
            $table->string('initial', 50)->nullable()->comment('ชื่อย่อหน่วยรับรอง')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_ibcbs', function (Blueprint $table) {
            $table->dropColumn('initial');
        });
    }
}
