<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppCertiTrackingAuditorsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_certi_tracking_auditors_status', function (Blueprint $table) {
            $table->increments('id'); 
            $table->unsignedInteger('auditors_id')->nullable()->comment('TB :app_certi_tracking_auditors');
            $table->integer('status_id')->nullable()->comment('TB :bcertify_status_auditors');
            $table->decimal('amount',15,2)->nullable()->comment('จำนวนเงิน');
            $table->integer('amount_date')->nullable()->comment('จำนวนวัน');
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
        Schema::dropIfExists('app_certi_tracking_auditors_status');
    }
}
