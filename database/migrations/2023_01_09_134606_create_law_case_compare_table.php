<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseCompareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_compare', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_cases_id')->nullable()->comment('ID : law_cases');
            $table->string('case_number',255)->nullable()->comment('เลขคดี');
            $table->string('book_number',255)->nullable()->comment('เลขที่หนังสือ');
            $table->date('book_date')->nullable()->comment('ลงวันที่');
            $table->decimal('total',12,2)->nullable()->comment('จำนวนเงิน');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก ID : user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข ID : user_register');

            $table->foreign('law_cases_id')
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
        Schema::dropIfExists('law_case_compare');
    }
}
