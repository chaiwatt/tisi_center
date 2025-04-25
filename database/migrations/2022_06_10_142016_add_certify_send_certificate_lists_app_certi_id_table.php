<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifySendCertificateListsAppCertiIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_send_certificate_lists', function (Blueprint $table) {
            $table->integer('app_certi_id')->nullable()->comment('ID ตารางคำขอ')->after('certificate_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_send_certificate_lists', function (Blueprint $table) {
            $table->dropColumn(['app_certi_id']);
        });
    }
}
