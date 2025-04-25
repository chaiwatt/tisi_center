<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifySendCertificateListsSignPathTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_send_certificate_lists', function (Blueprint $table) {
            $table->integer('sign_path')->nullable()->comment('path เก็บรูปลายเซ็นผู้ลงนาม')->after('app_certi_id');
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
            $table->dropColumn(['sign_path']);
        });
    }
}
