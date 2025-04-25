<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawBsCategoryOperateToLawBasicStatusOperateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_status_operate', function (Blueprint $table) {
            $table->integer('law_bs_category_operate_id')->nullable()->after('title')->comment('ID : law_basic_category_operate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_basic_status_operate', function (Blueprint $table) {
            $table->dropColumn(['law_bs_category_operate_id']); 
        });
    }
}
