<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptInfoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_receipt_info_details', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->text('taxid')->nullable()->comment('เลขประจำตัวผู้เสียภาษี');
            $table->text('name')->nullable()->comment('ชื่อ-สกุล');
            $table->text('address')->nullable()->comment('ที่อยู่');
            $table->text('email')->nullable()->comment('อีเมล');
            $table->text('tel')->nullable()->comment('เบอร์โทร');

            $table->integer('bs_bank_id')->nullable()->comment('ID : basic_banks');
            $table->text('bank_book_name')->nullable()->comment('ชื่อสมุดบัญชีธนาคาร');
            $table->text('bank_book_number')->nullable()->comment('หมายเลขสมุดบัญชีธนาคาร');
            $table->text('bank_book_file')->nullable()->comment('ไฟล์สมุดบัญชีธนาคาร');

            $table->boolean('state')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->nullable();
            $table->text('receipt_no')->nullable()->comment('เลขที่ใบเสร็จ');


            $table->unsignedInteger('receipt_info_id');
            $table->foreign('receipt_info_id')
                  ->references('id')
                  ->on('ac_receipt_info')
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
        Schema::dropIfExists('ac_receipt_info_details');
    }
}
