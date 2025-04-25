<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingAuditorsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_auditors_list', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('auditors_id')->nullable()->comment('TB :app_certi_tracking_auditors');
            $table->unsignedInteger('auditors_status_id')->nullable()->comment('TB :app_certi_tracking_auditors_status');
            $table->integer('user_id')->nullable()->comment('id TB : user_register');
            $table->string('temp_users',255)->nullable();
            $table->string('temp_departments',255)->nullable();
            $table->timestamps();
            $table->foreign('auditors_status_id')
                ->references('id')
                ->on('app_certi_tracking_auditors_status')
                ->onDelete('cascade');
            $table->foreign('auditors_id')
                ->references('id')
                ->on('app_certi_tracking_auditors')
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
        Schema::dropIfExists('app_certi_tracking_auditors_list');
    }
}
