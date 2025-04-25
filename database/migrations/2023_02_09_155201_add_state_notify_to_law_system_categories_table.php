<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateNotifyToLawSystemCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_system_categories', function (Blueprint $table) {
            $table->boolean('state')->nullable()->comment(' 1:ใช้งาน,0:ไม่ใช้งาน');
            $table->boolean('state_notify')->nullable()->comment('ระบบ Notify 1:แสดง,0:ไม่แสดง');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_system_categories', function (Blueprint $table) {
            $table->dropColumn(['state','state_notify','updated_by', 'created_by']);
            
        });
    }
}
