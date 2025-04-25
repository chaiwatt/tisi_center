<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppCertiTransactionPayInBillCreateDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_transaction_pay_in', function (Blueprint $table) {
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
            $table->decimal('PayAmountBill',12,2)->nullable()->comment('จำนวนเงินที่จ่าย');
        });
    }
 
    /** pay  amount bill
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_transaction_pay_in', function (Blueprint $table) {
            $table->dropColumn(['BankCode','BillCreateDate','Etc1Data','Etc2Data','InvoiceCode','PaymentDate','ReceiptCode','ReceiptCreateDate','ReconcileDate','SourceID','PayAmountBill']);
        });
    }
}
