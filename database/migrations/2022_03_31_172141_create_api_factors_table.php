<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiFactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_factors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->comment('ไอดีการเรียกใช้ API');
            $table->string('agent_id',255)->nullable()->comment('ค่าเลขบัตรประชาชนที่ส่งไป');

            $table->string('ampName',255)->nullable()->comment('ชื่ออำเภอ');
            $table->string('cap',255)->nullable()->comment('เงินทุน');
            $table->string('colonyIndustDesc',255)->nullable()->comment('นิคมอุตสาหกรรม');
            $table->string('dispFacReg',20)->nullable()->comment('เลขทะเบียนโรงงาน');
            $table->date('expDate')->nullable()->comment('วันสิ้นอายุ');
            $table->integer('facType')->nullable()->comment('ประเภทโรงงาน');
            $table->text('fAddr')->nullable()->comment('ที่ตั้งโรงงาน');
            $table->string('fFlag')->nullable()->comment('สถานะโรงงาน');
            $table->string('fID')->nullable()->comment('เลขทะเบียนโรงงาน (ใหม่)');
            $table->string('fMoo')->nullable()->comment('หมู่');
            $table->string('fName',255)->nullable()->comment('ชื่อโรงงาน');
            $table->string('HP',50)->nullable()->comment('แรงม้า');
            $table->integer('IndustType')->nullable()->comment('ประเภทการประกอบกิจการ');
            $table->string('Object',255)->nullable()->comment('การประกอบกิจการโรงงาน');
            $table->string('OName',255)->nullable()->comment('ชื่อผู้รับใบอนุญาต');
            $table->string('ProvName',255)->nullable()->comment('ชื่อจังหวัด');
            $table->string('Road',100)->nullable()->comment('ถนน');
            $table->string('Soi',100)->nullable()->comment('ซอย');
            $table->date('StartDate')->nullable()->comment('วันเริ่มประกอบกิจการโรงงาน');
            $table->string('Trade',100)->nullable()->comment('เลขประจำตัวประชาชน/เลขทะเบียนพาณิชย์');
            $table->string('TumName',100)->nullable()->comment('ชื่อตำบล');
            $table->string('Works',100)->nullable()->comment('คนงาน');
            $table->string('ZipCode',20)->nullable()->comment('รหัสไปรษณีย์');
            $table->string('ZoneDesc',255)->nullable()->comment('เขตประกอบการอุตสาหกรรม');

            $table->timestamps();
            $table->foreign('request_id')
            ->references('id')
            ->on('api_requests')
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
        Schema::dropIfExists('api_factors');
    }
}
