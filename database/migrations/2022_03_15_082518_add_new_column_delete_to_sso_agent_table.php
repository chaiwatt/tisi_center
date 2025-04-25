<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnDeleteToSsoAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_agent', function (Blueprint $table) {
            $table->text('remarks_delete')->nullable()->comment('หมายเหตุลบข้อมูล');
            $table->integer('delete_by')->nullable()->comment('ผู้ลบข้อมูล');
            $table->timestamp('delete_at')->nullable()->comment('วันที่ลบ');

            $table->string('head_mobile',50)->nullable()->comment('หมายเลขโทรศัพท์มือถือ ผู้มอบ')->after('head_telephone');
            $table->string('agent_mobile',50)->nullable()->comment('หมายเลขโทรศัพท์มือถือ ผู้รับมอบ')->after('agent_telephone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_agent', function (Blueprint $table) {
            $table->dropColumn(['remarks_delete', 'delete_by', 'delete_at', 'head_mobile', 'agent_mobile']);
        });
    }
}
