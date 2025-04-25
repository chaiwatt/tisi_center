<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseLicenseResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_license_result', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_case_id')->nullable()->comment('ID : law_cases');
            $table->integer('offend_tb4_tisilicense_id')->nullable()->comment('ความผิด : ใบอนุณาต (Autono : tb4_tisilicense)');
            $table->string('offend_license_number')->nullable()->comment('เงื่อนไขเปอร์เซ็นเงินที่หักได้ : เลขที่ใบอนุณาต');
            $table->integer('status')->nullable()->comment('สถานะ : 1.รอดำเนินการ, 2.อยู่ระหว่างดำเนินการ, 3.ดำเนินการเสร็จสิ้น');
            $table->integer('status_result')->nullable()->comment('สถานะ : 1.ใช้งาน, 2.พักใช้, 3.เพิกถอน');
            $table->date('date_pause_start')->nullable()->comment('วันที่เริ่มพักใช้');
            $table->date('date_pause_end')->nullable()->comment('วันที่สิ้นสุดพักใช้');
            $table->date('date_revoke')->nullable()->comment('วันที่เพิกถอน');
            $table->integer('basic_revoke_type_id')->nullable()->comment('ID : law_basic_revoke_type');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->foreign('law_case_id')
                    ->references('id')
                    ->on('law_cases')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('law_case_license_result');
    }
}
