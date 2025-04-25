<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_inspectors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->integer('inspector_id')->nullable()->comment('ID :ผู้ตรวจ/ผู้ประเมิน');
            $table->string('inspector_prefix',50)->nullable()->comment('คำนำหน้า');
            $table->string('inspector_first_name',255)->nullable()->comment('ชื่อ');
            $table->string('inspector_last_name',255)->nullable()->comment('นามสกุล');
            $table->string('inspector_taxid',255)->nullable()->comment('เลขนิติบุคคลผู้เสียภาษี');
            $table->integer('inspector_type')->nullable()->comment('ประเภท (1 = ผู้ตรวจของหน่วยตรวจ, 2 = Freelance)');
            $table->timestamps();
            $table->foreign('application_id')
                    ->references('id')
                    ->on('section5_application_ibcb')
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
        Schema::dropIfExists('section5_application_ibcb_inspectors');
    }
}
