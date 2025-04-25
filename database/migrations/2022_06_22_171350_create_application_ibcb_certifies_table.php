<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbCertifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_cer', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->integer('certificate_std_id')->nullable()->comment('มอก. ของใบรับรอง');
            $table->integer('certificate_id')->nullable()->comment('ID: ใบรับรอง');
            $table->text('certificate_table')->nullable()->comment('TB: ใบรับรอง');
            $table->text('certificate_no')->nullable()->comment('เลขที่ใบรับรอง');
            $table->date('certificate_start_date')->nullable()->comment('วันที่ออกใบรับรอง');
            $table->date('certificate_end_date')->nullable()->comment('วันที่ออกใบรับรอง');
            $table->integer('issued_by')->nullable()->comment('ออกให้ 1 สมอ. 2 อื่นๆ');

            $table->timestamps();
            $table->foreign('application_id')
                    ->references('id')
                    ->on('section5_application_ibcb')
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
        Schema::dropIfExists('section5_application_ibcb_cer');
    }
}
