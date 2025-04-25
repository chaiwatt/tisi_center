<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiJuristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_juristics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_id')->comment('ไอดีการเรียกใช้ API');
            $table->string('agent_id',255)->nullable()->comment('ค่าเลขบัตรประชาชนที่ส่งไป');
            $table->string('juristicType',255)->nullable()->comment('ประเภทนิติบุคคล เช่น บริษัทจำกัด เป็นต้น');
            $table->string('juristicID',50)->nullable()->comment('เลขทะเบียนนิติบุคคล (13 หลัก)');
            $table->string('oldJuristicID',50)->nullable()->comment('เลขทะเบียนนิติบุคคลเดิม');
            $table->date('registerDate')->nullable()->comment('วัน-เดือน-ปี ค.ศ. ที่ขึ้นทะเบียนนิติบุคคล โดยมีรูปแบบ YYYY-MM-DD');
            $table->string('juristicName_TH',255)->nullable()->comment('ชื่อนิติบุคคล (ภาษาไทย)');
            $table->string('juristicName_EN',255)->nullable()->comment('ชื่อนิติบุคคล (ภาษาอังกฤษ)');
            $table->string('registerCapital',50)->nullable()->comment('ทุนจดทะเบียนนิติบุคคล');
            $table->string('paidRegisterCapital',50)->nullable()->comment('ทุนจดทะเบียนนิติบุคคลที่ชำระแล้ว');
            $table->integer('numberOfObjective')->nullable()->comment('จำนวนข้อจุดประสงค์');
            $table->integer('numberOfPageOfObjective')->nullable()->comment('จำนวนหน้าจุดประสงค์');
            $table->string('juristicStatus',100)->nullable()->comment('สถานะปัจจุบันของนิติบุคคล');
            $table->string('standardID',50)->nullable();
            $table->text('authorizeDescriptions')->nullable()->comment('อำนาจคณะกรรมการนิติบุคคล');
            $table->text('standardObjectives')->nullable()->comment('จุดประสงค์ของธุรกิจ');
            $table->text('addressInformations')->nullable()->comment('ข้อมูลที่อยู่');
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
        Schema::dropIfExists('api_juristics');
    }
}
