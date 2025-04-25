<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSsoApplicationInspectorRegisterSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sso_application_inspector_register_subs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sso_application_inspector_register_subs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inspector_register_id')->nullable()->comment('ID คำขอขึ้นทะเบียน ผู้ตรวจ และผู้ประเมินของผู้ตรวจสอบการทำผลิตภัณฑ์อุตสาหกรรม');
            $table->integer('branch_group_id')->nullable()->comment('หมวดอุตสากรรม สาขา');
            $table->string('branch')->nullable()->comment('รายสาขา');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }
}
