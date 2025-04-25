<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBasicBoardTypesExpertGroupIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_board_types', function (Blueprint $table) {
            $table->integer('expert_group_id')->nullable()->after('title')->comment('กลุ่มประเภทคณะกรรมการ TB : basic_expert_groups');
        });
    }

    /**
     * Reverse the migrations. 
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_board_types', function (Blueprint $table) {
            $table->dropColumn(['expert_group_id']);
        });
    }
}
