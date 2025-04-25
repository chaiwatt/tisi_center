<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5ApplicationInspectorsScopeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_inspectors_scope', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->nullable()->comment('ไอดีคำขอ');
            $table->string('application_no', 30)->nullable()->comment('เลขที่คำขอ');
            $table->integer('branch_id')->nullable()->comment('ไอดีรายสาขา');
            $table->integer('branch_group_id')->nullable()->comment('ไอดีหมวดอุตสาหกรรม/สาขา');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้อัพเดท');
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
        Schema::dropIfExists('section5_application_inspectors_scope');
    }
}
