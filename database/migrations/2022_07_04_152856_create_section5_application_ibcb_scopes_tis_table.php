<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSection5ApplicationIbcbScopesTisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_ibcb_scopes_tis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ibcb_scope_id')->nullable();
            $table->integer('tis_id')->nullable()->comment('ไอดี มอก.');
            $table->string('tis_no', 255)->nullable()->comment('เลข มอก.');
            $table->string('tis_name')->nullable()->comment('เลข มอก.');
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
        Schema::dropIfExists('section5_application_ibcb_scopes_tis');
    }
}
