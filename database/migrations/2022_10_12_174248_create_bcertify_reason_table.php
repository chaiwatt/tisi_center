<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcertifyReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_reason', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->nullable()->comment('เหตุผลและความจะเป็น');
            $table->integer('condition')->nullable()->comment('เงื่อนไขอ้างอิง 1 = ใช้งาน');
            $table->boolean('state')->nullable()->comment('สถานะ 1 = ใช้งาน');
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
        Schema::dropIfExists('bcertify_reason');
    }
}
