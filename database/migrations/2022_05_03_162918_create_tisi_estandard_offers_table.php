<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTisiEstandardOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tisi_estandard_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255)->nullable()->comment('ชื่อมาตรฐาน');
            $table->string('title_eng',255)->nullable()->comment('ชื่อมาตรฐาน eng');
            $table->integer('std_type')->nullable()->comment('ประเภทมาตรฐาน');
            $table->text('scope')->nullable()->comment('ขอบข่าย');
            $table->text('objectve')->nullable()->comment('จุดประสงค์และเหตุผล');
            $table->text('path')->nullable()->comment('ชื่อ Path ที่เก็บ');
            $table->text('caption')->nullable()->comment('รายละเอียด');
            $table->text('attach_old')->nullable()->comment('ชื่อไฟล์ที่อัพโหลด');
            $table->text('attach_new')->nullable()->comment('ชื่อไฟล์ที่รับใหม่');
            $table->text('attach_type')->nullable()->comment('นามสกุลไฟล์');
            $table->string('stakeholders',255)->nullable()->comment('ผู้มีส่วนได้เสียที่เกี่ยวข้อง');
            $table->string('name',255)->nullable()->comment('ชื่อ-สกุลผู้เสนอ');
            $table->string('telephone',20)->nullable()->comment('เบอร์โทร');
            $table->integer('department_id')->nullable()->comment('หน่วยงาน/ต้นสังกัด ID TB : basic_departments');
            $table->string('department',255)->nullable()->comment('หน่วยงาน/ต้นสังกัด title TB : basic_departments');
            $table->string('email',20)->nullable()->comment('อีเมล');
            $table->text('address')->nullable()->comment('ที่อยู่');
            $table->string('ip_address',45)->nullable()->comment('ip address ที่ให้ความเห็น');
            $table->string('user_agent',255)->nullable()->comment('โปรแกรมเบราเซอร์ที่ใช้งาน');
            $table->integer('state')->nullable()->comment('สถานะ 1.เสนอความเห็น, 2.สมควรบรรจุในแผ,น 3.ไม่สมควรบรรจุในแผน, 4.จัดทำแผน');
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
        Schema::dropIfExists('tisi_estandard_offers');
    }
}
