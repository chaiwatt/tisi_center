<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawBasicDivisionTypeDivisionCategoryIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_division_type', function (Blueprint $table) {
            $table->integer('division_category_id')->nullable()->comment('id ตาราง law_basic_division_category')->after('title');
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
            $table->dropColumn(['division_category_id']);
        });
    }
}
