<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('bank_code')->nullable()->comment('รหัสธนาคาร');
            $table->text('title')->nullable()->comment('ชื่อธนาคาร');
            $table->text('title_en')->nullable()->comment('ชื่อธนาคาร (EN)');
            $table->string('title_short')->nullable()->comment('ชื่อธนาคารย่อ');
            $table->text('image')->nullable()->comment('รูปภาพธนาคาร');
            $table->string('com_code', 10)->nullable()->comment('Company Code สำหรับชำระเงินที่เคาน์เตอร์ธนาคาร');
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
        Schema::dropIfExists('basic_banks');
    }
}
