<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifySendCertificateListsSignPathChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_send_certificate_lists', function (Blueprint $table) {
            $table->string('sign_path',255)->nullable()->comment('path เก็บรูปลายเซ็นผู้ลงนาม')->change();
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
            $table->integer('sign_path')->nullable()->comment('path เก็บรูปลายเซ็นผู้ลงนาม')->change();
        });
    }
}
