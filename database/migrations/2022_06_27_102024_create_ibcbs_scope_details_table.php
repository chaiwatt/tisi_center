<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbcbsScopeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_ibcbs_scopes_details', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ibcb_scope_id')->nullable();
            $table->integer('branch_id')->nullable()->comment('ไอดีรายสาขา');
            $table->integer('ibcb_id')->nullable();
            $table->string('ibcb_code',255)->nullable()->comment('รหัสหน่วยตรวจสอบ');
            $table->tinyInteger('audit_result')->nullable()->comment('ผลตรวจประเมิน 1 = ผ่าน, 2 = ไม่ผ่าน');

            $table->timestamps();
            $table->foreign('ibcb_scope_id')
            ->references('id')
            ->on('section5_ibcbs_scopes')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section5_ibcbs_scopes_details');
    }
}
