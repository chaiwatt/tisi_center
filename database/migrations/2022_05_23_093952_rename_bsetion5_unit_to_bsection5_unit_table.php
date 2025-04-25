<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameBsetion5UnitToBsection5UnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('bsetion5_unit', 'bsection5_unit');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsection5_unit', function (Blueprint $table) {
            Schema::rename('bsection5_unit', 'bsetion5_unit');
        });
    }
}
