<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIbcbScopeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_scopes_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ibcb_scope_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->integer('branch_id')->nullable()->comment('ไอดีรายสาขา');
            $table->timestamps();
            $table->foreign('ibcb_scope_id')
                    ->references('id')
                    ->on('section5_application_ibcb_scopes')
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
        Schema::dropIfExists('section5_application_ibcb_scopes_details');
    }
}
