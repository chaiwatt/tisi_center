<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertifySignCertificateConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_sign_certificate_confirms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('send_certificate_list_id')->nullable()->comment('ID ตารางcertify_send_certificate_lists');
            $table->integer('certificate_otp_id')->nullable()->comment('ID ตาราง certify_sign_certificate_otp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certify_sign_certificate_confirms');
    }
}
