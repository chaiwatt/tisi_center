<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationLabSummaryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section5_application_labs_summary_details', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('app_summary_id')->nullable();
         
            $table->integer('application_lab_id')->nullable();
            $table->string('application_no')->nullable()->comment('เลขที่อ้างอิงคำขอ');

            $table->text('meeting_no')->nullable()->comment('ลำดับที่ประชุม');
            $table->text('agenda_no')->nullable()->comment('วาระ ( audit_type 1 : 5.2.1, audit_type 2 : 5.2.2  ) ');

            $table->boolean('state')->nullable()->comment('ผ่านการประชุม');

            $table->timestamps();

            $table->foreign('app_summary_id')
                    ->references('id')
                    ->on('section5_application_labs_summary')
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
        Schema::dropIfExists('section5_application_labs_summary_details');
    }
}
