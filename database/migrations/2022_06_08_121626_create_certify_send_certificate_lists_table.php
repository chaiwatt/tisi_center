<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertifySendCertificateListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_send_certificate_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('send_certificate_id')->nullable()->comment('id ตาราง certify_send_certificates');
            $table->integer('certificate_type')->nullable()->comment('ประเภทใบรับรอง 1-CB , 2-IB , 3-LAB');
            $table->string('certificate_tb', 255)->nullable()->comment('ตารางใบรับรองจามประเภท');
            $table->integer('certificate_id')->nullable()->comment('ID ตารางใบรับรองจามประเภท');
            $table->integer('sign_status')->nullable()->comment('สถานะการลงนาม 1-อยู่ระหว่าง , 2-ลงนามเรียบร้อย , 3-ไม่อนุมัติการลงนาม');
            $table->timestamps();
            $table->foreign('send_certificate_id')
                    ->references('id')
                    ->on('certify_send_certificates')
                    ->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certify_send_certificate_lists');
    }
}
