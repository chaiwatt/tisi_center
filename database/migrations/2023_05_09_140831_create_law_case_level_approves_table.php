<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseLevelApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_level_approves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_cases_id')->nullable()->comment('ID ตาราง law_cases');
            $table->integer('level')->nullable()->comment('ลำดับการอนุมัติ (ตัวเลขลำดับ)');
            $table->string('send_department')->nullable()->comment('ข้อมูลจาก department');
            $table->text('authorize_name')->nullable()->comment('ชื่อผู้มีอำนาจ');
            $table->text('position')->nullable()->comment('ข้อมูลจาก sub_department');
            $table->boolean('acting')->nullable()->comment('1=รักษาการแทน');
            $table->integer('authorize_userid')->nullable()->comment('ชื่อผู้มีอำนาจ user_id');
            $table->integer('status')->nullable()->comment('1=รอดำเนินการ, 2=รอพิจารณา, 3=เห็นควร, 4=ไม่เห็นควร');
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
        Schema::dropIfExists('law_case_level_approves');
    }
}
