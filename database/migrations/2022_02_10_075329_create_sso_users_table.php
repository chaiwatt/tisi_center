<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSsoUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sso_users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 400)->default('')->index('idx_name');
			$table->string('username', 150)->default('')->index('username');
			$table->string('email', 100)->default('')->index('email');
			$table->string('contact_name')->comment('ชื่อผู้ติดต่อเจ้าของ E-mail');
			$table->string('password', 100)->default('');
			$table->boolean('block')->default(0)->index('idx_block');
			$table->boolean('sendEmail')->nullable()->default(0);
			$table->dateTime('registerDate')->nullable();
			$table->dateTime('lastvisitDate')->nullable();
			$table->string('activation', 100)->default('');
			$table->text('params', 65535);
			$table->dateTime('lastResetTime')->nullable()->comment('Date of last password reset');
			$table->integer('resetCount')->default(0)->comment('Count of password resets since lastResetTime');
			$table->string('otpKey', 1000)->default('')->comment('Two factor authentication encrypted keys');
			$table->string('otep', 1000)->default('')->comment('One time emergency passwords');
			$table->boolean('requireReset')->default(0)->comment('Require user to reset password on next login');
			$table->string('applicanttype_id', 30)->nullable()->comment('ประเภทผู้สมัคร');
			$table->date('date_niti')->nullable();
			$table->string('tax_number', 30)->nullable();
			$table->string('nationality', 30)->nullable();
			$table->date('date_of_birth')->nullable();
			$table->string('prefix_name', 30)->nullable();
			$table->string('address_no', 100)->nullable();
			$table->string('street', 80)->nullable();
			$table->string('moo', 30)->nullable();
			$table->string('soi', 50)->nullable();
			$table->string('subdistrict', 70)->nullable();
			$table->string('district', 70)->nullable();
			$table->string('province', 70)->nullable();
			$table->string('zipcode', 5)->nullable();
			$table->string('tel', 30)->nullable();
			$table->string('fax', 30)->nullable();
			$table->string('head_street', 80)->nullable();
			$table->string('head_address_no', 100)->nullable();
			$table->string('head_moo', 30)->nullable();
			$table->string('head_soi', 50)->nullable();
			$table->string('head_subdistrict', 70)->nullable();
			$table->string('head_district', 70)->nullable();
			$table->string('head_province', 70)->nullable();
			$table->string('head_zipcode', 5)->nullable();
			$table->string('head_tel', 30)->nullable();
			$table->string('head_fax', 30)->nullable();
			$table->text('attfile', 65535)->nullable();
			$table->string('personfile')->nullable()->comment('สำเนาบัตรประจำตัวประชาชนในกรณีผู้เป็นบุคคลธรรมดา');
			$table->string('corporatefile')->nullable()->comment('หนังสือรับรองหรือสำเนาใบสำคัญของกรมพัฒนาธุรกิจการค้า กระทรวงพาณิชย์ แสดงชื่อผู้มีอำนาจทำการแทนนิติบุคคล (ไม่เกิน 6 เดือน)');
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
			$table->string('token_otp')->nullable()->comment('Token OTP');
			$table->string('consumer_secret')->nullable()->comment('ConsumerSecret ');
			$table->string('agent_id')->nullable()->comment('AgentID');
			$table->string('consumer_key')->nullable()->comment('Consumer-Key ');
			$table->string('deleted_at')->nullable();
			$table->text('remember_token', 65535)->nullable();
			$table->integer('state')->nullable()->comment('1.รอยืนยันตัวตนทาง E-mail 2.ยืนยันตัวตนแล้ว');
			$table->string('system')->nullable();
			$table->string('person_type')->nullable();
			$table->string('branch_code')->nullable();
			$table->string('building')->nullable();
			$table->string('country_code')->nullable();
			$table->string('head_building')->nullable();
			$table->string('head_country_code')->nullable();
			$table->string('contact_tax_id')->nullable();
			$table->string('contact_prefix_name')->nullable();
			$table->string('contact_prefix_text')->nullable();
			$table->string('contact_first_name')->nullable();
			$table->string('contact_last_name')->nullable();
			$table->string('contact_tel')->nullable();
			$table->string('contact_fax')->nullable();
			$table->string('contact_phone_number')->nullable();
			$table->string('prefix_text')->nullable();
			$table->string('person_first_name')->nullable();
			$table->string('person_last_name')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sso_users');
	}

}
