<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegisterExpertsCommitteeSpecialsIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->string('committee_specials_id',255)->nullable()->after('pic_profile')->comment('อ้างอิงคำสั่งที่');
            $table->integer('status')->nullable()->after('revoke_by')->comment('1.ยื่นคำขอ, 2.อยู่ระหว่างการตรวจสอบคำขอ, 3.ตีกลับคำขอ, 4.ตรวจสอบคำขอแก้ไข, 5.เอกกสารผ่านการตรวจสอบ, 6.อนุมัติการขึ้นทะเบียน, 7.ยกเลิกคำขอ, 8.ยกเลิกผู้เชี่ยวชาญ');
            $table->integer('state')->nullable()->comment('1.เปิด , 0.ปิด')->change();
        });
    }

    /**
     * Reverse the migrations.
     * 
     * @return void
     */
    public function down()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->dropColumn(['committee_specials_id','status']);
            $table->integer('state')->nullable()->comment('1.ยื่นคำขอ, 2.อยู่ระหว่างการตรวจสอบคำขอ, 3.ตีกลับคำขอ, 4.ตรวจสอบคำขอแก้ไข, 5.เอกกสารผ่านการตรวจสอบ, 6.อนุมัติการขึ้นทะเบียน, 7.ยกเลิกคำขอ, 8.ยกเลิกผู้เชี่ยวชาญ')->change();
        });
    }
}
