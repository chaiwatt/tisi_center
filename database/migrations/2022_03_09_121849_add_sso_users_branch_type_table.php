<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSsoUsersBranchTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->enum('branch_type', ['1', '2'])->nullable()->comment('ประเภทสาขา 1.สำนักงานใหญ่, 2.สาขา')->after('person_type');
            $table->string('contact_position', 255)->nullable()->comment('ตำแหน่ง')->after('contact_last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->dropColumn(['branch_type','contact_position']);
        });
    }
}
