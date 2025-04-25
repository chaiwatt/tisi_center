<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditorTypeToSsoUsersHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users_historys', function (Blueprint $table) {
            $table->string('editor_type', 255)
                  ->nullable()
                  ->comment('ประเภทผู้แก้ไข staff=เจ้าหน้าที่, owner=เจ้าของบัญชี, system:ชื่อระบบ=แก้ไขโดยระบบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_users_historys', function (Blueprint $table) {
            $table->dropColumn(['editor_type']);
        });
    }
}
