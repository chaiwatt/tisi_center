<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuditorInformationsTaxIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auditor_informations', function (Blueprint $table) {
            $table->string('tax_id',15)->nullable()->after('user_id')->comment('เลขบัตรประชาชน');
            $table->integer('created_by')->nullable()->after('tax_id')->comment('id ตาราง sso_users ผู้บันทึก');
            $table->integer('agent_id')->nullable()->after('created_by')->comment('id ตาราง sso_users ผู้รับมอบอำนาจที่ดำเนินการแทน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auditor_informations', function (Blueprint $table) {
            $table->dropColumn(['tax_id','created_by','agent_id']);
        });
    }
}
 