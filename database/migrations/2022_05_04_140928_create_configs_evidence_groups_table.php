<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsEvidenceGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs_evidence_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('system')->nullable()->comment('ระบบ');
            $table->string('code')->nullable()->comment('รหัส');
            $table->string('title')->nullable()->comment('ชื่อกลุ่มไฟล์แนบ');
            $table->text('short_title')->nullable()->comment('ชื่อย่อกลุ่มไฟล์แนบ');
            $table->text('remarks')->nullable()->comment('รายละเอียด');
            $table->text('section_web')->nullable()->comment('เงื่อนไขการแยกไฟล์ front-end : ส่วนหน้า , front-end : ส่วนหลัง ');
            $table->integer('ordering')->nullable();
            $table->integer('state')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('configs_evidence_groups');
    }
}
