<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyFieldfileInSsoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->string('personfile', 512)->nullable()->comment('สำเนาบัตรประจำตัวประชาชนในกรณีผู้เป็นบุคคลธรรมดา')->change();
			$table->string('corporatefile', 512)->nullable()->comment('หนังสือรับรองหรือสำเนาใบสำคัญของกรมพัฒนาธุรกิจการค้า กระทรวงพาณิชย์ แสดงชื่อผู้มีอำนาจทำการแทนนิติบุคคล (ไม่เกิน 6 เดือน)')->change();
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
            $table->string('personfile')->nullable()->comment('สำเนาบัตรประจำตัวประชาชนในกรณีผู้เป็นบุคคลธรรมดา')->change();
			$table->string('corporatefile')->nullable()->comment('หนังสือรับรองหรือสำเนาใบสำคัญของกรมพัฒนาธุรกิจการค้า กระทรวงพาณิชย์ แสดงชื่อผู้มีอำนาจทำการแทนนิติบุคคล (ไม่เกิน 6 เดือน)')->change();
        });
    }
}
