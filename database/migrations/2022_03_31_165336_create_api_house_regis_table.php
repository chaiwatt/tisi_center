<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiHouseRegisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_house_regis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->comment('ไอดีการเรียกใช้ API');
            $table->string('agent_id',255)->nullable()->comment('ค่าเลขบัตรประชาชนที่ส่งไป');
            $table->string('citizenID',255)->nullable()->comment('เลขบัตรประชาชน');

            $table->string('alleyCode',100)->nullable()->comment('รหัสซอย');
            $table->string('alleyDesc')->nullable()->comment('ชื่อซอย');

            $table->string('AlleyWayCode',100)->nullable()->comment('รหัสตรอก');
            $table->string('AlleyWayDesc',255)->nullable()->comment('ชื่อตรอก');

            $table->string('districtCode',100)->nullable()->comment('รหัสอำเภอ');
            $table->string('districtDesc',255)->nullable()->comment('ชื่ออำเภอ');

            $table->string('houseID',100)->nullable()->comment('รหัสบ้าน');
            $table->string('houseNo',100)->nullable()->comment('เลขที่บ้าน');
            $table->integer('houseType')->nullable()->comment('ประเภทบ้าน');
            $table->integer('houseTypeDesc')->nullable()->comment('ชื่อประเภทบ้าน');

            $table->string('provinceCode',100)->nullable()->comment('รหัสจังหวัด');
            $table->string('provinceDesc',255)->nullable()->comment('ชื่อจังหวัด');

            $table->string('rcodeCode',100)->nullable();
            $table->string('rcodeDesc',255)->nullable();

            $table->string('roadCode',100)->nullable()->comment('รหัสถนน');
            $table->string('roadDesc',255)->nullable()->comment('ชื่อถนน');

            $table->string('subdistrictCode',100)->nullable()->comment('รหัสตำบล');
            $table->string('subdistrictDesc',255)->nullable()->comment('ชื่อตำบล');

            $table->string('villageNo',255)->nullable()->comment('หมู่ที่บ้าน');

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
        Schema::dropIfExists('api_house_regis');
    }
}
