<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDropColumnAppLapIdToSection5ApplicationLabsGazetteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_gazette', function (Blueprint $table) {
            $table->dropForeign(['app_lab_id']);
            $table->dropColumn(['app_lab_id','application_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_gazette', function (Blueprint $table) {
            $table->unsignedInteger('app_lab_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');
            $table->foreign('app_lab_id')
                ->references('id')
                ->on('section5_application_labs')
                ->onDelete('cascade');
        });
    }
}
