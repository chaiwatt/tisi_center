<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingStandardRecordCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certify_setstandard_meeting_record_cost', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meeting_record_id')->nullable()->comment('id ตาราง certify_setstandard_meeting_record');
            $table->integer('setstandard_id')->nullable()->comment('id ตาราง certify_setstandards');
            $table->string('expense_other', 255)->nullable()->comment('รายการค่าใช้จ่ายอื่นๆ');
            $table->decimal('cost',10,2)->nullable()->comment('ค่าใช้จ่ายในการประชุม');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certify_setstandard_meeting_record_cost');
    }
}
