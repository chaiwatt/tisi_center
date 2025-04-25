<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpaymentBillTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epayment_bill_test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Ref1',255)->nullable()->comment('เลขที่คำขอ');
            $table->string('CGDRef1',255)->nullable();
            $table->decimal('Amount',12,2)->nullable()->comment('จำนวนเงินที่จ่าย');
            $table->integer('Status')->nullable()->comment('สถานะการชำระ');
            $table->string('BankCode',255)->nullable()->comment('รหัสธนาคาร');
            $table->datetime('BillCreateDate')->nullable()->comment('วันที่สร้างบิล');
            $table->string('Etc1Data',255)->nullable();
            $table->string('Etc2Data',255)->nullable();
            $table->string('InvoiceCode',255)->nullable()->comment('รหัสใบแจ้งหนี้');
            $table->datetime('PaymentDate')->nullable()->comment('วันจ่าย');
            $table->string('ReceiptCode',255)->nullable()->comment('รหัสใบเสร็จรับเงิน');
            $table->datetime('ReceiptCreateDate')->nullable()->comment('วันที่สร้างใบเสร็จรับเงิน');
            $table->datetime('ReconcileDate')->nullable()->comment('วันที่กระทบยอด');
            $table->string('SourceID',255)->nullable()->comment('รหัสแหล่งที่มา');
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
        Schema::dropIfExists('epayment_bill_test');
    }
}
