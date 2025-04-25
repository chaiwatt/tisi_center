<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawBasicDivisionTypeRewardGroupIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_division_type', function (Blueprint $table) {
            $table->text('reward_group_id')->nullable()->comment('กลุ่มผู้มีสิทธิ์ได้รับเงิน TB : law_basic_reward_group')->after('division_category_id');
            $table->dropColumn(['average']);
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
            $table->dropColumn(['reward_group_id']);
            $table->text('average')->nullable()->comment('เฉลี่ย เก็บ (array)')->after('division_category_id');
        });
    }
}
