<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbcbsScopeTisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_ibcbs_scopes_tis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ibcb_scope_id')->nullable();
            $table->integer('tis_id')->nullable()->comment('ไอดี มอก.');
            $table->string('tis_no',255)->nullable()->comment('เลข มอก.');
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
        Schema::dropIfExists('section5_ibcbs_scopes_tis');
    }
}
