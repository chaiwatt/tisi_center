<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingAuditorsDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_auditors_date', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('auditors_id')->nullable()->comment('TB :app_certi_tracking_auditors');
            $table->date('start_date')->nullable()->comment('วันที่เริ่มต้มตรวจประเมิน');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุนตรวจประเมิน');
            $table->timestamps();
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
        Schema::dropIfExists('app_certi_tracking_auditors_date');
    }
}
