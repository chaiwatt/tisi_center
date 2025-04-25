<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbInspectorsScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_inspectors_scopes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ibcb_inspector_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->integer('branch_group_id')->nullable()->comment('ไอดีหมวดอุตสาหกรรม/สาขา');
            $table->integer('branch_id')->nullable()->comment('ไอดีรายสาขา');
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
        Schema::dropIfExists('section5_application_ibcb_inspectors_scopes');
    }
}
