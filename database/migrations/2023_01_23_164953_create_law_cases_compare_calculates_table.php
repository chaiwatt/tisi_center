<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCasesCompareCalculatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_compare_calculates', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('law_case_result_section_id')->nullable()->comment('ID : law_case_result_section');

            $table->integer('cal_type')->nullable()->comment('คำนวณเงิน ประเภทการคำนาณ 1.สัดส่วน(%), 2.จำนวนเงิน');
            $table->integer('division')->nullable()->comment('คำนวณเงิน : สัดส่วน(%)');
            $table->decimal('total_value',30,2)->nullable()->comment('คำนวณเงิน : มูลค่าผลิตภัณฑ์');
            $table->decimal('amount',30,2)->nullable()->comment('คำนวณเงิน : จำนวนเงิน');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก ID : user_register');

            $table->unsignedInteger('law_case_compare_id')->nullable()->comment('ID ตาราง law_case_compare');

            $table->foreign('law_case_compare_id')
                    ->references('id')
                    ->on('law_case_compare')
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
        Schema::dropIfExists('law_case_compare_calculates');
    }
}
