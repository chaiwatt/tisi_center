<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawOffenderCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_offenders_cases', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('law_offender_id')->nullable()->comment('ID : law_offenders_cases');
            $table->integer('law_cases_id')->nullable()->comment('ID ตาราง law_cases');
            $table->string('case_number', 255)->nullable()->comment('เลขคดี');
            $table->date('date_offender_case')->nullable()->comment('วันที่พบการกระทำผิดครั้งแรก');
            $table->integer('tb4_tisilicense_id')->nullable()->comment('ใบอนุญาต อ้างอิงตาราง  tb4_tisilicense');
            $table->string('license_number')->nullable()->comment('เลขที่ใบอนุญาต');
            $table->integer('tis_id')->nullable()->comment('มอก. id อ้างอิงตาราง tb3_tis');
            $table->string('tb3_tisno')->nullable()->comment('มอก. number');
            $table->text('section')->nullable()->comment('ฝ่าฝืนตามมาตรา (json) อ้างอิง:law_basic_section');
            $table->text('punish')->nullable()->comment('บทลงโทษ (json) อ้างอิง:law_basic_section');
            $table->enum('case_person', ['0', '1'])->default('0')->comment('ดำเนินการทางอาญา (ผู้กระทำความผิด) 0.ไม่ดำเนินการ, 1.ดำเนิน');
            $table->enum('case_license', ['0', '1'])->default('0')->comment('ดำเนินการปกครอง (ใบอนุญาต) 0.ไม่ดำเนินการ, 1.ดำเนิน');
            $table->enum('case_product', ['0', '1'])->default('0')->comment('ดำเนินการของกลาง (ผลิตภัณฑ์) 0.ไม่ดำเนินการ, 1.ดำเนิน');
            $table->date('date_close')->nullable()->comment('วันที่ปิดคดี');
            $table->integer('status')->comment('สถานะ 1 = รอดำเนินการ 2 = อยู่ระหว่างดำเนินการ 3 = ปิดงานคดี');
            $table->bigInteger('lawyer_by')->nullable()->comment('นิติกรเจ้าคดี');
            
            $table->foreign('law_offender_id')
                    ->references('id')
                    ->on('law_offenders_cases')
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
        Schema::dropIfExists('law_offenders_cases');
    }
}
