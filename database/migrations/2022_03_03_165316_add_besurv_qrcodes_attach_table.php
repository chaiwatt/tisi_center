<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBesurvQrcodesAttachTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('besurv_qrcodes', function (Blueprint $table) {
            $table->string('attach_state',1)->nullable()->comment('เปิดใช้งาน ไฟล์แนบ');
            $table->string('attach')->nullable()->comment('ไฟล์แนบ');
            $table->string('file_client_name')->nullable()->comment('ชื่อไฟล์แนบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('besurv_qrcodes', function (Blueprint $table) {
            $table->dropColumn(['attach_state','attach','file_client_name']);
        });
    }
}
