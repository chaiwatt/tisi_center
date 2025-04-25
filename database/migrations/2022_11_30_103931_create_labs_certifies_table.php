<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabsCertifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_labs_certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('lab_id')->nullable();
            $table->string('lab_code', 255)->nullable()->comment('ห้องปฏิบัติการ: รหัสปฏิบัติการ');
            $table->string('ref_lab_application_no', 255)->nullable()->comment('อ้างอิงเลขที่คำขอ');

            $table->string('certificate_no')->nullable()->comment('เลขที่ใบรับรอง.');
            $table->integer('certificate_id')->nullable()->comment('id : certificate_exports');
            $table->dateTime('certificate_start_date')->nullable()->comment('วันที่เริ่ม.');
            $table->dateTime('certificate_end_date')->nullable()->comment('วันหมดอายุ.');
            $table->string('accereditatio_no')->nullable()->comment('หมายเลขการรับรอง');
            $table->integer('issued_by')->nullable()->comment('ออกให้โดย 1 = สมอ. 2 = อื่นๆ');

            $table->string('renew_certificate_no')->nullable()->comment('เลขที่ใบรับรองเดิม');
            $table->integer('renew_certificate_id')->nullable()->comment('id : certificate_exports');
    
            $table->integer('application_labs_cer_id')->nullable()->comment('id : section5_application_labs_cer');

            $table->timestamps();
            $table->foreign('lab_id')
                    ->references('id')
                    ->on('section5_labs')
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
        Schema::dropIfExists('section5_labs_certificates');
    }
}
