<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCommentStateInSsoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->integer('state')->nullable()->comment('1=รอยืนยันตัวตนทาง E-mail, 2=ยืนยันตัวตนแล้ว, 3=รอเจ้าหน้าที่เปิดใช้งาน')->change();
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
            $table->integer('state')->nullable()->comment('1.รอยืนยันตัวตนทาง E-mail 2.ยืนยันตัวตนแล้ว')->change();
        });
    }
}
