<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationLabCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_cer', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_lab_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->string('certificate_no')->nullable()->comment('เลขที่ใบรับรอง.');

            $table->dateTime('certificate_start_date')->nullable()->comment('วันที่เริ่ม.');
            $table->dateTime('certificate_end_date')->nullable()->comment('วันหมดอายุ.');
            $table->integer('issued_by')->nullable()->comment('ออกให้โดย 1 = สมอ. 2 = อื่นๆ');

            $table->timestamps();
            $table->foreign('application_lab_id')
                    ->references('id')
                    ->on('section5_application_labs')
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
        Schema::dropIfExists('section5_application_labs_cer');
    }
}
