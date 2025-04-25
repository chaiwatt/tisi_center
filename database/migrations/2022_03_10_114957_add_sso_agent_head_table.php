<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSsoAgentHeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_agent', function (Blueprint $table) {
 

            $table->string('head_name',255)->nullable()->comment('ชื่อ ผู้มอบ')->after('user_id');
            $table->string('head_address_no',255)->nullable()->comment('ที่ตั้ง ผู้มอบ')->after('head_name');
            $table->string('head_village',100)->nullable()->comment('อาคาร/หมู่บ้าน ผู้มอบ')->after('head_address_no');
            $table->string('head_moo',100)->nullable()->comment('หมู่ ผู้มอบ')->after('head_village');
            $table->string('head_soi',50)->nullable()->comment('ซอย ผู้มอบ')->after('head_moo');
            $table->string('head_subdistrict',255)->nullable()->comment('ตำบล/แขวง ผู้มอบ')->after('head_soi');
            $table->string('head_district',255)->nullable()->comment('อำเภอ/เขต ผู้มอบ')->after('head_subdistrict');
            $table->string('head_province',255)->nullable()->comment('จังหวัด ผู้มอบ')->after('head_district');
            $table->string('head_zipcode',15)->nullable()->comment('รหัสไปรษณีย์ ผู้มอบ')->after('head_province');
            $table->string('head_telephone',50)->nullable()->comment('หมายเลขโทรศัพท์ ผู้มอบ')->after('head_zipcode');

            $table->string('agent_name',255)->nullable()->comment('ชื่อ ผู้รับมอบ')->after('agent_id');
            $table->string('agent_address_no',255)->nullable()->comment('ที่ตั้ง ผู้รับมอบ')->after('agent_name');
            $table->string('agent_village',100)->nullable()->comment('อาคาร/หมู่บ้าน ผู้รับมอบ')->after('agent_address_no');
            $table->string('agent_moo',100)->nullable()->comment('หมู่ ผู้รับมอบ')->after('agent_village');
            $table->string('agent_soi',50)->nullable()->comment('ซอย ผู้รับมอบ')->after('agent_moo');
            $table->string('agent_subdistrict',255)->nullable()->comment('ตำบล/แขวง ผู้รับมอบ')->after('agent_soi');
            $table->string('agent_district',255)->nullable()->comment('อำเภอ/เขต ผู้รับมอบ')->after('agent_subdistrict');
            $table->string('agent_province',255)->nullable()->comment('จังหวัด ผู้รับมอบ')->after('agent_district');
            $table->string('agent_zipcode',15)->nullable()->comment('รหัสไปรษณีย์ ผู้รับมอบ')->after('agent_province');
            $table->string('agent_telephone',50)->nullable()->comment('หมายเลขโทรศัพท์ ผู้รับมอบ')->after('agent_zipcode');

            $table->integer('confirm_status')->nullable()->comment('ยืนยันสถานะ 1.-ยืนยัน 2.-ไม่ยืนยัน')->after('state');
            $table->date('confirm_date')->nullable()->comment('วันที่ยืนยันสถานะ')->after('confirm_status');

            $table->date('revoke_date')->nullable()->comment('วันที่บันทึกการการสิ้นสุดการมอบหมาย')->after('confirm_date');
            $table->text('revoke_detail')->nullable()->comment('รายละเอียดการสิ้นสุด')->after('revoke_date');
            $table->integer('revoke_by')->nullable()->comment('ผู้บันทึกกการสิ้นสุนการมอบหมาย')->after('revoke_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_agent', function (Blueprint $table) {
        
            $table->dropColumn(['head_name','head_address_no','head_village','head_moo','head_soi','head_subdistrict','head_district','head_province','head_telephone',
                                'agent_name','agent_address_no','agent_village','agent_moo','agent_soi','agent_subdistrict','agent_district','agent_province','agent_telephone',
                                'confirm_status','confirm_date','revoke_date','revoke_detail','revoke_by'
                            ]);
        });
    }
}
