<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLawCaseLevelApprovesCausesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_case_level_approves', function (Blueprint $table) {
            $table->text('causes')->nullable()->after('status')->comment('เนื่องจาก');
            $table->integer('return_to')->nullable()->after('causes')->comment('ส่งเรื่องกลับไปยัง (id ตาราง user_register)');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_case_level_approves', function (Blueprint $table) {
            $table->dropColumn(['causes', 'return_to']);
        });
    }
}
 