<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawBookManageVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_book_manage_visit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('section_id')->nullable()->comment('session id ของผู้ใช้งาน');
            $table->integer('law_book_manage_id')->nullable()->comment('id ตาราง law_book_manage');
            $table->integer('system_type')->nullable()->comment('1 = sso(บุคคลทั่วไป), 2= center(เจ้าหน้าทึ่)');
            $table->integer('action')->nullable()->comment('1=เข้าชม, 2=ดาวโหลด');
            $table->dateTime('visit_at')->nullable();
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
        Schema::dropIfExists('law_book_manage_visit');
    }
}
