<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawBasicDivisionTypeAverageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_division_type', function (Blueprint $table) {
             $table->text('average')->nullable()->comment('เฉลี่ย เก็บ (array)')->after('division_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_basic_division_type', function (Blueprint $table) {
            $table->dropColumn(['average']);
        });
    }
}
