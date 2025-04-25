<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewColumnAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs', function (Blueprint $table) {
            $table->text('assign_by')->nullable()->comment('ผู้ได้รับมอบหมาย');
            $table->dateTime('assign_date')->nullable()->comment('วันที่มอบหมาย');
            $table->text('assign_comment')->nullable()->comment('ความคิดเห็นเพิ่มเติมมอบหมาย');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs', function (Blueprint $table) {
            $table->dropColumn(['assign_by', 'assign_date', 'assign_comment']);
        });
    }
}
