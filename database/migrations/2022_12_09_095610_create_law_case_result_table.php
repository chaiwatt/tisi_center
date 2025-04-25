<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_result', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_case_id')->nullable()->comment('ID ตาราง law_cases');
            $table->enum('person', ['0', '1'])->default('0')->comment('ดำเนินการทางอาญา (ผู้กระทำความผิด) 0.ไม่ดำเนินการ, 1.ดำเนิน');
            $table->enum('license', ['0', '1'])->default('0')->comment('ดำเนินการปกครอง (ใบอนุญาต) 0.ไม่ดำเนินการ, 1.ดำเนิน');
            $table->enum('product', ['0', '1'])->default('0')->comment('ดำเนินการของกลาง (ผลิตภัณฑ์) 0.ไม่ดำเนินการ, 1.ดำเนิน');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก');
            $table->bigInteger('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('law_case_result');
    }
}
