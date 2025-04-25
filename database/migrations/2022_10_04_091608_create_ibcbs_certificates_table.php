<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbcbsCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_ibcbs_certificates', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ibcb_id')->nullable();
            $table->string('ibcb_code',255)->nullable()->comment('รหัสหน่วยตรวจสอบ');

            $table->integer('certificate_std_id')->nullable()->comment('มอก. ของใบรับรอง');
            $table->integer('certificate_id')->nullable()->comment('ID: ใบรับรอง');
            $table->text('certificate_table')->nullable()->comment('TB: ใบรับรอง');
            $table->text('certificate_no')->nullable()->comment('เลขที่ใบรับรอง');
            $table->date('certificate_start_date')->nullable()->comment('วันที่ออกใบรับรอง');
            $table->date('certificate_end_date')->nullable()->comment('วันที่ออกใบรับรอง');
            $table->integer('issued_by')->nullable()->comment('ออกให้ 1 สมอ. 2 อื่นๆ');
            $table->integer('type')->default(1)->comment('ประเภท 1 = ใบสมัคร, 2 = ระบบ IBCB ');
            
            $table->timestamps();
            $table->foreign('ibcb_id')
                    ->references('id')
                    ->on('section5_ibcbs')
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
        Schema::dropIfExists('section5_ibcbs_certificates');
    }
}
