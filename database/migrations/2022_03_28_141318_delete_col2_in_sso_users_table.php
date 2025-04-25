<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteCol2InSsoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->dropColumn(['department_id',
                                'authorize_name',
                                'authorize_id_no',
                                'copy_card_authorize',
                                'agency_name',
                                'agency_id_no',
                                'agency_tel',
                                'copy_card_agency',
                                'letter_of_authority',
                                'authorize',
                                'authorize_data',
                                'requireSign',
                                'sign_tax_number',
                                'sign_name',
                                'sign_position',
                                'sign_img',
                                'deleted_at',
                                'country_code',
                                'contact_country_code',
                                'attfile',
                                'head_tel',
                                'head_fax',
                                'system'
                            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->integer('department_id')->unsigned()->comment('รหัสกลุ่มงาน');
            $table->string('authorize_name')->nullable()->comment('ชื่อ-นามสกุล  ผู้มอบอำนาจ');
            $table->string('authorize_id_no', 13)->nullable()->comment('เลข 13 หลักผู้มอบอำนาจ');
			$table->text('copy_card_authorize', 65535)->nullable()->comment('สำเนาบัตรผู้มอบอำนาจ');
			$table->string('agency_name')->nullable()->comment('ชื่อ-นามสกุล ผู้รับมอบอำนาจ');
			$table->string('agency_id_no', 13)->nullable()->comment('13 หลักผู้รับมอบอำนาจ');
			$table->string('agency_tel', 30)->comment('เบอร์โทรผู้รับมอบอำนาจ');
			$table->text('copy_card_agency', 65535)->nullable()->comment('สำเนาบัตรผู้รับมอบอำนาจ');
			$table->text('letter_of_authority', 65535)->nullable()->comment('หนังสือมอบอำนาจ');
			$table->enum('authorize', array('0','1'))->default('0')->comment('0=\'ไม่มอบอำนาจ\', 1=\'มอบอำนาจ\'');
			$table->text('authorize_data', 65535)->comment('รายละเอียดผู้มอบอำนาจ');
			$table->string('requireSign', 11)->nullable()->comment('เป็นผู้อำนาจลงนาม 1. เป็น 0. ไม่เป็น');
			$table->string('sign_tax_number')->nullable()->comment('เลขประจำตัวผู้เสียภาษี 13 หลัก');
			$table->string('sign_name')->nullable()->comment('ผู้มีอำนามลงนาม');
			$table->string('sign_position')->nullable()->comment('ตำแหน่ง ');
			$table->text('sign_img', 65535)->nullable()->comment('ลายเซ็นผู้ลงนาม');
            $table->string('deleted_at')->nullable();
            $table->string('country_code')->nullable();
            $table->string('contact_country_code')->nullable();
            $table->text('attfile', 65535)->nullable();
            $table->string('head_tel', 30)->nullable();
			$table->string('head_fax', 30)->nullable();
            $table->string('system')->nullable();
        });
    }
}
