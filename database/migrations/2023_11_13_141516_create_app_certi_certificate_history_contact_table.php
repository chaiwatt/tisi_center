<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiCertificateHistoryContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_certificate_history_contact', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ref_id')->nullable()->comment('id ตารางใบรับรอง ตามประเภทใบรับรอง');
            $table->string('ref_table', 255)->nullable()->comment('ชื่อตารางตามประเภทใบรับรอง');
            $table->string('contact_name', 255)->nullable()->comment('ชื่อผู้ติดต่อ');
            $table->string('contact_tel', 50)->nullable()->comment('เบอโทรศัพท์');
            $table->string('contact_mobile', 50)->nullable()->comment('เบอร์มือถือ');
            $table->string('contact_email', 255)->nullable()->comment('email');
            $table->unsignedInteger('created_by')->nullable()->comment('คนที่แก้');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_certi_certificate_history_contact');
    }
}
