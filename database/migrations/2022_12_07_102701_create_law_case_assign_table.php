<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_assign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_case_id')->nullable()->comment('id ตาราง law_case');
            $table->integer('sub_department_id')->nullable()->comment('กลุ่มงาน/กอง sub_id ตาราง sub_department');
            $table->integer('assign_by')->nullable()->comment('ผู้รับมอบหมาย (ผก.) runrecno ตาราง user_register');
            $table->text('lawyer_by')->nullable()->comment('นิติกรเจ้าของคดี (json) runrecno ตาราง user_register');
            $table->bigInteger('created_by')->nullable()->comment('ผู้บันทึก runrecno ตาราง user_register');
            $table->bigInteger('updated_by')->nullable()->comment(' ผู้แก้ไข ตาราง user_register');
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
        Schema::dropIfExists('law_case_assign');
    }
}
