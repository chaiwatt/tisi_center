<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5InspectorsScopeTis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_inspectors_scope_tis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inspector_scope_id')->nullable()->comment('id ตาราง section5_inspectors_scopes');
            $table->string('inspectors_code',255)->nullable()->comment('รหัสผู้ตรวจประเมิน inspectors_code ตาราง section5_inspectors');
            $table->integer('tis_id')->nullable()->comment('id ตาราง tis_standards');
            $table->string('tis_no', 255)->nullable()->comment('เลข มอก.');
            $table->string('tis_name', 1024)->nullable()->comment('ชื่อ มอก.');
            $table->tinyInteger('state')->default(1)->comment('สถานะ 1 = ใช้งาน, 2 = ไม่ใช้งาน');
            $table->timestamps();
            $table->foreign('inspector_scope_id')
                    ->references('id')
                    ->on('section5_inspectors_scopes')
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
        Schema::dropIfExists('section5_inspectors_scope_tis');
    }
}
