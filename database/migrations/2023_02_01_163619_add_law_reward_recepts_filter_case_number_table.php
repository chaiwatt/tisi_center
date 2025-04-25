<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawRewardReceptsFilterCaseNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_reward_recepts', function (Blueprint $table) {
            $table->string('filter_case_number')->nullable()->comment('เบิกค่าใช้จ่ายในคดี :  เลขคดี')->after('recepts_type');
            $table->string('filter_paid_date_month',2)->nullable()->comment('เบิกค่าใช้จ่ายในคดี : เดือน')->after('filter_case_number');
            $table->string('filter_paid_date_year',5)->nullable()->comment('เบิกค่าใช้จ่ายในคดี : เดือน')->after('filter_paid_date_month');
            $table->date('filter_paid_date_start')->nullable()->comment('เบิกค่าใช้จ่ายในคดี : วันที่เริ่ม')->after('filter_paid_date_year');
            $table->date('filter_paid_date_end')->nullable()->comment('เบิกค่าใช้จ่ายในคดี : วันที่สิ้นสุด')->after('filter_paid_date_start');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_reward_recepts', function (Blueprint $table) {
            $table->dropColumn([
                'filter_case_number','filter_paid_date_month','filter_paid_date_year','filter_paid_date_start','filter_paid_date_end'
            ]);
        });
    }
}
