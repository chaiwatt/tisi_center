<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegisterExpertsTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->string('token', 255)->nullable()->after('updated_by');
            $table->integer('deleted_by')->nullable()->after('token')->comment('ผู้ยกเลิก');
            $table->dateTime('deleted_at')->nullable()->after('deleted_by')->comment('วันที่ยกเลิก');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->dropColumn(['token','deleted_by', 'deleted_at']); 
        });
    }
}
