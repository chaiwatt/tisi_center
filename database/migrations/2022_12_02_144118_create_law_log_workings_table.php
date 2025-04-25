<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawLogWorkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_log_working', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_system_category_id')->nullable()->comment('ID : ระบบงานหลัก');
            $table->string('ref_table',255)->nullable()->comment('ชื่อตาราง');
            $table->integer('ref_id')->nullable()->comment('ID : ref_table');
            $table->string('ref_no',255)->nullable()->comment('เลขที่อ้างอิง');
            $table->string('ref_system',255)->nullable()->comment('ชื่อระบบงานย่อย');
            $table->string('title',255)->nullable()->comment('ชื่อเรื่อง');
            $table->string('status',255)->nullable()->comment('เก็บข้อมูลเป็น text');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('law_log_working');
    }
}
