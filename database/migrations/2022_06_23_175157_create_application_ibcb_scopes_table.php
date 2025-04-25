<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_scopes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('application_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->integer('branch_group_id')->nullable()->comment('ไอดีหมวดอุตสาหกรรม/สาขา');
            $table->string('isic_no')->nullable()->comment('ISIC NO');
            $table->text('tis_no')->nullable()->comment('เลขที่มอก. json');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
            $table->timestamps();
            $table->foreign('application_id')
                    ->references('id')
                    ->on('section5_application_ibcb')
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
        Schema::dropIfExists('section5_application_ibcb_scopes');
    }
}
