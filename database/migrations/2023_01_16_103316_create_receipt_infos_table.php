<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_receipt_info', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('receipt_no')->nullable()->comment('เลขที่ใบเสร็จ');
            $table->integer('depart_type')->nullable()->comment('1(ภายใน), 2(ภายนอก)');
            $table->text('department_name')->nullable()->comment('ภายใน : เก็บชื่อ สำนักงานมาตรฐานผลิตอุตสาหกรรม(สมอ.), ภายนอก : ระบุ ชื่อบริษัท/หน่วยงาน');
            $table->boolean('state')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_receipt_info');
    }
}
