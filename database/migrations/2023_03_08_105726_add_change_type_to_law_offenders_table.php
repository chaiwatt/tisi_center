<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeTypeToLawOffendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_offenders_cases', function (Blueprint $table) {
            $table->string('sub_department_id')->nullable()->comment('เจ้าของคดี : กอง/กลุ่ม (กรณีภายใน) ตารางอ้างอิง sub_department')->change();
            $table->string('basic_department_id')->nullable()->comment('เจ้าของคดี : ชื่อหน่วยงาน (กรณีภายนอก) ตารางอ้างอิง law_basic_department')->change();
            $table->text('department_name')->nullable()->comment('ชื่อหน่วยงาน/กอง/กลุ่ม')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_offenders_cases', function (Blueprint $table) {
            $table->integer('sub_department_id')->nullable()->comment('เจ้าของคดี : กอง/กลุ่ม (กรณีภายใน) ตารางอ้างอิง sub_department')->change();
            $table->integer('basic_department_id')->nullable()->comment('เจ้าของคดี : ชื่อหน่วยงาน (กรณีภายนอก) ตารางอ้างอิง law_basic_department')->change();
            $table->string('department_name')->nullable()->comment('ชื่อหน่วยงาน/กอง/กลุ่ม')->change();
        });
    }
}
